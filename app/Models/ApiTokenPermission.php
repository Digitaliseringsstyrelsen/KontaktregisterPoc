<?php

namespace Digist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiTokenPermission extends Model
{
    use SoftDeletes;

    const READ = 'read';
    const WRITE = 'write';
    const UNWASHED = 'un_washed';

    protected $fillable = [
        'name',
        'description',
        'granted_at',
        'revoked_at',
        'expired_at',
        'permission_key',
        'api_token_id',
    ];
}
