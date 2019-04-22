<?php
if (!function_exists("active")) {
    /**
     * @param $routeName
     *
     * @return string
     */
    function active($routeName) {
        return \EFLInventory\Support\View::routeIsActive($routeName);
    }
}
