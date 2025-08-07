<?php
namespace System\Core;

// ##################################################
// CLASS IN DEVELOPMENT, NOT TESTED!!!
// ##################################################
class FormValidator
{
    private array $data = [];
    private array $errors = [];
    private static array $customRules = [];

    /**
     * Resolve data source: use setForm, otherwise POST, then GET.
     */
    private function resolveSource(): array {
        if (!empty($this->data)) return $this->data;
        if (!empty($_POST)) return $_POST;
        return $_GET;
    }

    public function setForm(array $data): void {
        $this->data = $data;
    }

    public function get(string $key, mixed $default = null): mixed {
        $source = $this->resolveSource();
        return $source[$key] ?? $default;
    }

    public function has(string $key): bool {
        $source = $this->resolveSource();
        return isset($source[$key]);
    }

    /**
     * Resolves a dotted field path (with optional "..") into all matched values.
     *
     * Supports deep nesting and iteration over numeric arrays when using ".." in the path.
     *
     * For example:
     *  - "users..email" will return all emails from each user in the array.
     *  - "user.address.city" will return one value if found.
     *
     * @param array $data The input data (usually $_POST or manually injected).
     * @param string $path The dot-notation field path (supports ".." for arrays).
     * @return array Returns an associative array [resolved_path => value] for each matched field.
     */
    private function resolveFieldPath(array $data, string $path): array {
        $results = [];

        // Normalize path
        $segments = explode('.', $path);

        $this->walkPath($data, $segments, '', $results);

        return $results;
    }

    /**
     * Internal recursive utility for traversing a path structure and collecting resolved values.
     *
     * Automatically handles segments like "" (double-dot "..") for iterating over arrays.
     *
     * @param mixed $data Current level of the input data.
     * @param array $segments Remaining path segments to resolve.
     * @param string $prefix The accumulated key path for error referencing.
     * @param array &$results Output array to store resolved [path => value] results.
     * @return void
     */
    private function walkPath(mixed $data, array $segments, string $prefix, array &$results): void {
        $segment = array_shift($segments);

        if ($segment === null) {
            $results[$prefix] = $data;
            return;
        }

        if ($segment === '') {
            // double-dot: loop over numeric children
            if (!is_array($data)) return;

            foreach ($data as $key => $child) {
                $newPrefix = $prefix === '' ? $key : "$prefix.$key";
                $this->walkPath($child, $segments, $newPrefix, $results);
            }
        } elseif (isset($data[$segment])) {
            $newPrefix = $prefix === '' ? $segment : "$prefix.$segment";
            $this->walkPath($data[$segment], $segments, $newPrefix, $results);
        }
    }

    public static function registerRule(string $name, callable $fn): void {
        self::$customRules[$name] = $fn;
    }

    private function addError(string $field, string $message): void {
        $this->errors[$field][] = $message;
    }

    public function resetErrors(): void {
        $this->errors = [];
    }

    public function errors(): array {
        return $this->errors;
    }

    /**
     * Validates the input data based on a set of rules.
     *
     * Each rule key can use dot notation (e.g. "user.name") or double-dot syntax (e.g. "users..email")
     * to target nested arrays or iterate over multiple elements dynamically.
     *
     * Supported rules:
     *  - required
     *  - email
     *  - min:{length}
     *  - max:{length}
     *  - same:{other_field}
     *  - numeric
     *  - integer
     *  - date
     *  - regex
     *  - in:{values_separated_by_comma}
     *  - {custom_registered_handler}
     *
     * Examples:
     *  - 'email' => 'required|email'
     *  - 'users..email' => 'required|email'
     *   - 'user.addresses..city' => 'required|min:2'
     *   - 'user.emails..' => 'required|email'
     *
     * @param array $rules Associative array where the key is the input field path and the value is a pipe-separated rule list.
     * @return bool Returns true if all fields are valid, false otherwise.
     */
    public function validate(array $rules): bool {
        $this->errors = [];
        $source = $this->resolveSource();

        foreach ($rules as $fieldPath => $ruleStr) {
            $rulesArray = explode('|', $ruleStr);

            // Resolve values based on dot or double-dot notation
            $values = $this->resolveFieldPath($source, $fieldPath);

            foreach ($values as $path => $value) {
                foreach ($rulesArray as $rule) {
                    [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

                    switch ($ruleName) {
                        case 'required':
                            if (is_null($value) || trim((string)$value) === '') {
                                $this->addError($path, 'This field is required.');
                            }
                            break;

                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError($path, 'Invalid email address.');
                            }
                            break;

                        case 'min':
                            if (strlen((string)$value) < (int)$param) {
                                $this->addError($path, "Minimum length is $param characters.");
                            }
                            break;

                        case 'max':
                            if (strlen((string)$value) > (int)$param) {
                                $this->addError($path, "Maximum length is $param characters.");
                            }
                            break;

                        case 'same':
                            $other = $this->resolveFieldPath($source, $param);
                            $otherValue = reset($other);
                            if ($otherValue !== $value) {
                                $this->addError($path, "This field must match '$param'.");
                            }
                            break;

                        case 'numeric':
                            if (!is_numeric($value)) {
                                $this->addError($path, 'This field must be numeric.');
                            }
                            break;

                        case 'integer':
                            if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                                $this->addError($path, 'This field must be an integer.');
                            }
                            break;

                        case 'date':
                            if (strtotime((string)$value) === false) {
                                $this->addError($path, 'This field must be a valid date.');
                            }
                            break;

                        case 'regex':
                            if (!preg_match($param, (string)$value)) {
                                $this->addError($path, 'Invalid format.');
                            }
                            break;

                        case 'in':
                            $allowed = explode(',', $param);
                            if (!in_array((string)$value, $allowed, true)) {
                                $this->addError($path, 'Invalid value.');
                            }
                            break;

                        default:
                            if (isset(self::$customRules[$ruleName])) {
                                $fn = self::$customRules[$ruleName];
                                $result = $fn($value, $param, $path, $source);

                                if ($result === false) {
                                    $this->addError($path, "The field is invalid.");
                                } elseif (is_string($result)) {
                                    $this->addError($path, $result);
                                }
                            } else {
                                $this->addError($path, "Unknown rule: $ruleName");
                            }
                            break;
                    }
                }
            }
        }

        return empty($this->errors);
    }
}