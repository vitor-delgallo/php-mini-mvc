<?php
namespace App\Bootable;

use \System\Interfaces\IBootable;

class ExecAllRoutes implements IBootable {
    public static function boot(): void {
        //This will be executed in every route.
    }
}