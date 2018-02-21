<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackupConfig extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["account", "password", "account_key", "folder_name"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ["password", "key"];
}
