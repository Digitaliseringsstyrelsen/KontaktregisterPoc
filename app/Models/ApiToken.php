<?php

namespace Digist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class ApiToken extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    protected $fillable = [
        'name',
        'api_token',
        'description',
    ];

    public function permissions()
    {
        return $this->hasMany(ApiTokenPermission::class)->whereNull('revoked_at');
    }

    public function doesRequiredWashData() : bool
    {
        if (nemid()->check()) {
            return false;
        }

        return $this->permissions->filter(function ($val) {
            return $val->permission_key === ApiTokenPermission::UNWASHED;
        })->isNotEmpty();
    }

    public function requestPermissionRules()
    {
        return [
            'POST' => 'write',
            'PUT'  => 'write',
            'GET'  => 'read',
        ];
    }
}
