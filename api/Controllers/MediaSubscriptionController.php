<?php

namespace Api\Controllers;

use Api\Features\MediaSubscription\Create;
use Api\Features\MediaSubscription\Destroy;
use Api\Maps\MediaSubscriptionMap;
use Api\Models\Contact;
use Illuminate\Http\Request;

class MediaSubscriptionController extends BaseController implements ControllerInterface
{
    public function mapping()
    {
        return new MediaSubscriptionMap();
    }

    /**
     * @SWG\Post(
     *     path="/contact/{identifier}/media-subscriptions",
     *     summary="Create a contact media subscriptions",
     *     tags={"contact:media-subscription"},
     *     description="Create contact media.",
     *     operationId="contact-medias-create",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Contact"),
     *             @SWG\Items(ref="#/definitions/Media")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized",
     *     ),
     *     security={
     *         {
     *             "nemId": {"write:contact", "read:contact"},
     *             "basic-auth": {"read:contact"}
     *         }
     *     }
     * )
     * @param Request $request
     * @param Contact $contact
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function store(Contact $contact, Request $request)
    {
        return $this->serve(Create::class, [
            'contact' => $contact,
            'subscription_id' => $request->get('subscription_id'),
            'media_id' => $request->get('media_id'),
        ]);
    }

    /**
     * @SWG\Delete(
     *     path="/contact/{identifier}/media-subscriptions/{media_subscription_id}",
     *     summary="Delete a contact media subscriptions",
     *     tags={"contact:media-subscription"},
     *     description="Delete contact media.",
     *     operationId="contact-medias-delete",
     *     @SWG\Response(
     *         response=204,
     *         description="",
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized",
     *     ),
     *     security={
     *         {
     *             "nemId": {"write:contact", "read:contact"},
     *             "basic-auth": {"read:contact"}
     *         }
     *     }
     * )
     * @param Contact $contact
     * @param Request $request
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact, Request $request)
    {
        return $this->serve(Destroy::class, [
            'contact' => $contact,
            'subscription_id' => $request->get('subscription_id'),
            'media_id' => $request->get('media_id'),
        ]);
    }
}
