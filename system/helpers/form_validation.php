<?php

use \System\Core\FormValidator;

function form_validator_register_rule(string $name, callable $callback): void {
    FormValidator::registerRule($name, $callback);
}

/**
 * Singleton-style accessor for the current FormValidator instance.
 *
 * @param array|null $data Optional: set form data (only on first call).
 * @param bool $reset Whether to reset the instance.
 * @return FormValidator
 */
function form_validator(?array $data = null, bool $reset = false): FormValidator {
    static $instance = null;

    if ($reset || $instance === null) {
        $instance = new FormValidator();
    }

    if ($data !== null) {
        $instance->setForm($data);
    }

    return $instance;
}