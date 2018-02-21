<?php
/**
 *
 * Utility functions
 *
 * @author: Ekojoka Christolight Idakwo <chris.idakwo@gmail.com>
 * @date: 29/12/2017
 * @time: 08:46
 */

namespace App\Helpers;

class Functions {

    /**
     ** convert a hex string to rgb
     *
     * @param $hex_str
     * @param string $separator
     * @param bool $return_array
     * @return bool|array|string
     */
    public static function hex2rgb($hex_str, $separator=",", $return_array=false) {
        $hex_str = preg_replace("/[^0-9A-Fa-f]/", "", $hex_str);
        $rgb_array = [];

        if(strlen($hex_str) == 6) {
            $color_val = hexdec($hex_str);
            $rgb_array["r"] = 0xFF & ($color_val >> 0x10);
            $rgb_array["g"] = 0xFF & ($color_val >> 0x8);
            $rgb_array["b"] = 0xFF & $color_val;
        } elseif (strlen($hex_str) == 3) {
            $rgb_array["r"] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
            $rgb_array["g"] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
            $rgb_array["b"] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
        } else {
            return false;
        }

        return ($return_array) ? $rgb_array : implode($separator, $rgb_array);
    }
}
