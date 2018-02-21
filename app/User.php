<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', "name", "last_login"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Returns the name of assigned role
     * @return mixed
     */
    public function getRoleName() {
        $roles = $this->roles()->get()->first();
        return $roles["name"];
    }

    /**
     * @param $roles
     * @return bool
     */
    public function authorizeRoles($roles)
    {
        if(is_array($roles)) {
            return $this->hasAnyRole($roles);
        }

        return $this->hasRole($roles);
    }


    /**
     * Check multiple roles
     *
     * @param $roles
     * @return bool
     */
    public function hasAnyRole($roles) {
        return null !== $this->roles()->whereIn("name", $roles)->first();
    }

    /**
     * Check one role
     * @param $role
     * @return bool
     */
    public function hasRole($role) {
        return null !== $this->roles()->where("name", $role)->first();
    }
}
