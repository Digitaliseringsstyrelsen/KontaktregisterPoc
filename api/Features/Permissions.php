<?php

namespace Api\Features;

use Api\Exceptions\UnauthorizedException;
use Digist\Models\ApiToken;
use Digist\Models\ApiTokenPermission;
use Illuminate\Support\Facades\Auth;

trait Permissions
{
    private function validatePermissions()
    {
        /** @var ApiToken $authToken */
        $authToken = Auth::user();
        if ($authToken && $authToken instanceof ApiToken) {
            $this->permissions($authToken);
        }
    }

    private function permissions(ApiToken $apiToken)
    {
        $requiredPermission = array_filter($apiToken->requestPermissionRules(), function ($val){
            return $val === request()->getMethod();
        }, ARRAY_FILTER_USE_KEY);

        if (empty($requiredPermission)) {
            throw new UnauthorizedException();
        }

        if ($apiToken->permissions->filter(function(ApiTokenPermission $permission) use ($requiredPermission) {
            return $requiredPermission[request()->getMethod()] == $permission->permission_key;
        })->isEmpty()) {
            throw new UnauthorizedException();
        }
    }
}
