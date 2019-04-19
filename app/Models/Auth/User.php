<?php

namespace EFLInventory\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $visible = ["id"];

    protected $fillable = ['name', 'username', 'password', "is_admin", "is_staff", 'last_login'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        "is_admin" => "boolean",
        "is_staff" => "boolean",
        'email_verified_at' => 'datetime',
        "last_login" => "datetime"
    ];
}

