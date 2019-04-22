<?php

namespace EFLInventory\Support;


use Illuminate\Support\Facades\Route;

class View {
    /**
     * @param $routeName
     *
     * @return string
     */
    public static function routeIsActive($routeName): string {
        if ($routeName === Route::currentRouteName()) {
            return "active";
        }

        return "";
    }
}
