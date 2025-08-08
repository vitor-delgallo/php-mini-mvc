<?php
namespace System\Core;

/**
 * Class FormValidator
 *
 * Utility class for validating form input with support for nested structures and custom rules.
 *
 * Supports dot notation (`field.subfield`) and double-dot notation (`field..subfield`) for deep array traversal.
 *
 * @note This class is still under development and has not been fully tested.
 */
class FormValidator
{
    /**
     * The input data to be validated.
     *
     * Can be set manually via setForm(), or defaults to $_POST/$_GET if not set.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Accumulated validation errors after running validate().
     *
     * The array is structured as [field_path => list of error messages].
     *
     * @var array<string, string[]>
     */
    private array $errors = [];

    /**
     * Custom validation rules registered by the user.
     *
     * Each rule is a callable with the signature:
     * fn(mixed $value, ?string $param, string $fieldPath, array $source): bool|string|null
     *
     * @var array<string, callable>
     */
    private static array $customRules = [];

    /**
     * Resolves the form data source:
     * - If manually set via setForm(), uses that.
     * - Otherwise falls back to $_POST, then $_GET.
     *
     * @return array The resolved input data.
     */
    private function resolveSource(): array {
        if (!empty($this->data)) return $this->data;
        if (!empty($_POST)) return $_POST;
        return $_GET;
    }

    /**
     * Manually sets the form data to be validated.
     *
     * @param array $data The input form data.
     */
    public function setForm(array $data): void {
        $this->data = $data;
    }

    /**
     * Gets a value from the input data using a key.
     *
     * @param string $key The field name.
     * @param mixed $default Default value if not found.
     * @return mixed The value or default.
     */
    public function get(string $key, mixed $default = null): mixed {
        $source = $this->resolveSource();
        return $source[$key] ?? $default;
    }

    /**
     * Checks whether a field exists in the input data.
     *
     * @param string $key The field name.
     * @return bool True if the field exists, false otherwise.
     */
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

    /**
     * Registers a custom validation rule.
     *
     * @param string $name The name of the rule (used in the rules string).
     * @param callable $fn The validation function with signature fn($value, $param, $fieldPath, $fullSource).
     */
    public static function registerRule(string $name, callable $fn): void {
        self::$customRules[$name] = $fn;
    }

    /**
     * Adds an error message for a specific field.
     *
     * @param string $field Field path where the error occurred.
     * @param string $message Error message to add.
     */
    private function addError(string $field, string $message): void {
        $this->errors[$field][] = $message;
    }

    /**
     * Clears all accumulated validation errors.
     */
    public function resetErrors(): void {
        $this->errors = [];
    }

    /**
     * Returns the list of validation errors.
     *
     * @return array Associative array of errors keyed by field path.
     */
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
     *  - 'user.addresses..city' => 'required|min:2'
     *  - 'user.emails..' => 'required|email'
     *
     * @param array $rules Associative array where the key is the input field path and the value is a pipe-separated rule list.
     * @return bool Returns true if all fields are valid, false otherwise.
     */
    public function validate(array $rules): bool {
        $this->errors = [];
        $source = $this->resolveSource();

        foreach ($rules AS $fieldPath => $ruleStr) {
            $rulesArray = explode('|', $ruleStr);

            // Resolve values based on dot or double-dot notation
            $values = $this->resolveFieldPath($source, $fieldPath);

            foreach ($values AS $path => $value) {
                foreach ($rulesArray as $rule) {
                    [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

                    switch ($ruleName) {
                        case 'required':
                            if (is_null($value) || trim((string)$value) === '') {
                                $this->addError($path, lg('form_validator.error.required'));
                            }
                            break;

                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError($path, lg('form_validator.error.email'));
                            }
                            break;

                        case 'min':
                            if (strlen((string)$value) < (int)$param) {
                                $this->addError($path, lg('form_validator.error.min', ['param' => $param]));
                            }
                            break;

                        case 'max':
                            if (strlen((string)$value) > (int)$param) {
                                $this->addError($path, lg('form_validator.error.max', ['param' => $param]));
                            }
                            break;

                        case 'same':
                            $other = $this->resolveFieldPath($source, $param);
                            $otherValue = reset($other);
                            if ($otherValue !== $value) {
                                $this->addError($path, lg('form_validator.error.same', ['param' => $param]));
                            }
                            break;

                        case 'numeric':
                            if (!is_numeric($value)) {
                                $this->addError($path, lg('form_validator.error.numeric'));
                            }
                            break;

                        case 'integer':
                            if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                                $this->addError($path, lg('form_validator.error.integer'));
                            }
                            break;

                        case 'date':
                            if (strtotime((string)$value) === false) {
                                $this->addError($path, lg('form_validator.error.date'));
                            }
                            break;

                        case 'regex':
                            if (!preg_match($param, (string)$value)) {
                                $this->addError($path, lg('form_validator.error.regex'));
                            }
                            break;

                        case 'in':
                            $allowed = explode(',', $param);
                            if (!in_array((string)$value, $allowed, true)) {
                                $this->addError($path, lg('form_validator.error.in'));
                            }
                            break;

                        default:
                            if (isset(self::$customRules[$ruleName])) {
                                $fn = self::$customRules[$ruleName];
                                $result = $fn($value, $param, $path, $source);

                                if ($result === false) {
                                    $this->addError($path, lg('form_validator.error.invalid'));
                                } elseif (is_string($result)) {
                                    $this->addError($path, $result);
                                }
                            } else {
                                $this->addError($path, lg('form_validator.error.unknown', ['rule' => $ruleName]));
                            }
                            break;
                    }
                }
            }
        }

        return empty($this->errors);
    }
}