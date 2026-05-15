<?php

use \System\Core\PHPAutoload;

function php_autoload_from(string $directory): void {
    PHPAutoload::from($directory);
}

function php_autoload_boot(): void {
    PHPAutoload::boot();
}
