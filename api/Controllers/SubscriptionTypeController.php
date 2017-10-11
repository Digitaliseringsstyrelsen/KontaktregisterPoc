<?php

namespace Api\Controllers;

use Api\Features\SubscriptionTypes\Index;
use Api\Maps\SubscriptionTypeMap;

class SubscriptionTypeController extends BaseController implements ControllerInterface
{
    public function mapping()
    {
        return new SubscriptionTypeMap();
    }

    /**
     * @SWG\Get(
     *     path="/subscription-types",
     *     summary="List subscription types.",
     *     tags={"subscription-types"},
     *     description="List subscription types.",
     *     operationId="subscription-types",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/SubscriptionType"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized",
     *     ),
     *     security={
     *         {
     *             "nemId": {"read:contact"},
     *             "basic-auth": {"read:contact"}
     *         }
     *     }
     * )
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->serve(Index::class, []);
    }
}
