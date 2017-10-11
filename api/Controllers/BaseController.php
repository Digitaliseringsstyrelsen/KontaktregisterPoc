<?php

namespace Api\Controllers;

use Api\Features\ServeTrait;
use Digist\Http\Controllers\Controller;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="api.digital-registry.dk",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Digital Registry Api",
 *         description="",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="contact@mysite.com"
 *         ),
 *     )
 * )
 */
class BaseController extends Controller
{
    use ServeTrait;
}
