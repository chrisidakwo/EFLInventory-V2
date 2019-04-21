<?php

namespace EFLInventory\Support;

class DB {
    /**
     * @param $model
     * @param string $type
     * @param int $length
     *
     * @return string
     * @throws \Exception
     */
    public static function generateId($model, $type = "", $length = 8): string {
        $id = self::randomStr($type, $length);
        if (app($model)->where("id", $id)->exists()) {
            self::generateId($model);
        }

        return $id;
    }

    /**
     * @param string $type
     * @param int $length
     *
     * @return int|string
     * @throws \Exception
     */
    public static function randomStr($type = "alphanum", $length = 8) {
        switch($type) {
            case 'basic'    : return mt_rand();
                break;
            case 'alpha'    :
            case 'alphanum' :
            case 'num'      :
            case 'nozero'   :
                $seedings             = array();
                $seedings['alpha']    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['alphanum'] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['num']      = '0123456789';
                $seedings['nozero']   = '123456789';

                $pool = $seedings[$type];

                $str = '';
                for ($i=0; $i < $length; $i++) {
                    $str .= substr($pool, random_int(0, strlen($pool) -1), 1);
                }
                return $str;
                break;
            case 'unique'   :
            case 'md5'      :
                return md5(uniqid(mt_rand(), true));
                break;
        }


        return uniqid('', true);
    }
}
