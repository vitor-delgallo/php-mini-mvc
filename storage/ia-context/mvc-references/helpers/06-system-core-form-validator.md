# System\Core\FormValidator

Source: `system/Core/FormValidator.php`  
Helper source: `system/helpers/form_validation.php`  
Namespace: `System\Core`

Validates form data from an explicit array, `$_POST`, or `$_GET`. It supports dot paths for nested values and double-dot paths for iterating nested arrays.

## Static And Object Usage

```php
use System\Core\FormValidator;

FormValidator::registerRule('adult', function (mixed $value): bool {
    return (int) $value >= 18;
});

$validator = new FormValidator();
$validator->setForm(['age' => '21']);
$valid = $validator->validate(['age' => 'required|integer|adult']);
```

## Helper Usage

```php
form_validator_register_rule('adult', function (mixed $value): bool {
    return (int) $value >= 18;
});

$validator = form_validator(['age' => '21'], reset: true);
$valid = $validator->validate(['age' => 'required|integer|adult']);
```

## Method And Helper Signatures

| Method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `FormValidator::registerRule(string $name, callable $fn): void` | `form_validator_register_rule(string $name, callable $callback): void` | Rule name and callback. Callback receives `(mixed $value, ?string $param, string $fieldPath, array $source)`. | Nothing. |
| `new FormValidator()` | `form_validator(?array $data = null, bool $reset = false): FormValidator` | Optional data array and whether to reset the helper's singleton-like instance. | A `FormValidator` instance. |
| `$validator->setForm(array $data): void` | Use `form_validator($data)` | Explicit form data array. | Nothing. |
| `$validator->get(string $key, mixed $default = null): mixed` | No direct helper. | Top-level field name and default value. | Field value or default. |
| `$validator->has(string $key): bool` | No direct helper. | Top-level field name. | `true` when the field exists. |
| `$validator->resetErrors(): void` | No direct helper. | No arguments. | Nothing. |
| `$validator->errors(): array` | No direct helper. | No arguments. | Error array keyed by resolved field path. |
| `$validator->validate(array $rules): bool` | No direct helper. | Associative array where keys are field paths and values are pipe-separated rules. | `true` when all rules pass. |

## Built-In Rules

| Rule | Accepts |
| --- | --- |
| `required` | No parameter. Value must not be null or an empty trimmed string. |
| `email` | No parameter. Value must pass `FILTER_VALIDATE_EMAIL`. |
| `min:{length}` | Minimum string length. |
| `max:{length}` | Maximum string length. |
| `same:{other_field}` | Another field path that must equal the current value. |
| `numeric` | No parameter. Value must pass `is_numeric()`. |
| `integer` | No parameter. Value must pass `FILTER_VALIDATE_INT`. |
| `date` | No parameter. Value must be accepted by `strtotime()`. |
| `regex:{pattern}` | A PHP regex pattern accepted by `preg_match()`. |
| `in:{a,b,c}` | Comma-separated list of allowed string values. |
| Custom rule | Registered through `registerRule()` or `form_validator_register_rule()`. |

## Path Rules

- `user.email` resolves a nested field path.
- `users..email` iterates each item in `users` and validates its `email` value.
- `user.emails..` iterates each value inside `user.emails`.

## Notes

- The helper keeps a static instance. Pass `reset: true` when you need a clean validator.
- The class docblock marks this validator as still under development and not fully tested.
