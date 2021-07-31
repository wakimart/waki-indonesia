<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'code', 'name', 'username', 'password', 'permissions', 'active', 'user_image', 'birth_date', 'branches_id', 'cso_id', 'fcm_token',
    ];

    protected $casts = [
        'permissions' => 'array',
        'fmc_token' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    /**
     * Checks if User has access to $permissions.
     */
    public function hasAccess(array $permissions): bool
    {
        // check if the permission is available in any role
        // $tes = json_decode($this->permissions, true);
        // dd($tes["add-member"]);

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;

        // foreach ($this->roles as $role) {
        //     if($role->hasAccess($permissions)) {
        //         return true;
        //     }
        // }
        // return false;
    }

    public function hasPermission(string $permission): bool
    {
        // return $this->permissions[$permission] ?? false;
        $permissions = json_decode($this->permissions, true);
        return $permissions[$permission] ?? false;
    }

    /**
     * Checks if the user belongs to role.
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }

    public function listBranches()
    {
        $listBranch = json_decode($this['branches_id'], true);
        if ($listBranch == null) {
            return null;
        }
        return Branch::whereIn('id', $listBranch)->get();
    }

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function userGeolocation()
    {
        return $this->hasMany("App\UserGeolocation");
    }
}
