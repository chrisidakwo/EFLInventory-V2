<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["inner_exception", "error_message", "stack_trace"];

    public static function AddNewError($inner_exception, $error_message, $stack_trace) {
        $active_user = \Auth::User();
        ErrorLog::create([
            "active_user" => $active_user->username,
            "inner_exception" => $inner_exception,
            "error_message" => $error_message,
            "stack_trace" => $stack_trace
        ]);
    }
}
