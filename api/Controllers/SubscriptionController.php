<?php

namespace Api\Controllers;

use Api\Features\Subscriptions\AcceptTerms;
use Api\Features\Subscriptions\Create;
use Api\Features\Subscriptions\Destroy;
use Api\Features\Subscriptions\Get;
use Api\Features\Subscriptions\Index;
use Api\Features\Subscriptions\Update;
use Api\Maps\SubscriptionMap;
use Api\Models\Contact;
use Api\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends BaseController implements ControllerInterface
{
    public function mapping()
    {
        return new SubscriptionMap();
    }

    /**
     * @SWG\Get(
     *     path="/contact/{identifier}/subscriptions",
     *     summary="Get contact subscriptions",
     *     tags={"contact:subscription"},
     *     description="Get contact subscriptions.",
     *     operationId="contact-subscription",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Subscription"),
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
     * @param Contact $contact
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function index(Contact $contact)
    {
        return $this->serve(Index::class, [
            'contact' => $contact
        ]);
    }

    /**
     * @SWG\Get(
     *     path="/contact/{identifier}/subscriptions/{subscription_id}",
     *     summary="Get contact subscriptions",
     *     tags={"contact:subscription"},
     *     description="Get contact subscriptions.",
     *     operationId="contact-subscription-get",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Subscription"),
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
     * @param Contact $contact
     * @param Subscription $subscription
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function show(Contact $contact, Subscription $subscription)
    {
        return $this->serve(Get::class, [
            'contact' => $contact,
            'subscription' => $subscription,
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/contact/{identifier}/subscriptions",
     *     summary="Create a contact subscription",
     *     tags={"contact:subscription"},
     *     description="Create contact subscription.",
     *     operationId="contact-subscription-create",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Subscription"),
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
            'subscription_type_id' => $request->get('subscription_type_id'),
            'source_contact_id' => $request->get('source_contact_id'),
            'start_at' => $request->get('start_at'),
            'end_at' => $request->get('end_at'),
        ]);
    }

    /**
     * @SWG\Put(
     *     path="/contact/{identifier}/subscriptions/{subscription_id}",
     *     summary="Update a contact subscription",
     *     tags={"contact:subscription"},
     *     description="Create contact subscription.",
     *     operationId="contact-subscription-update",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Subscription"),
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
     * @param Contact $_
     * @param Subscription $subscription
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function update(Contact $_, Subscription $subscription, Request $request)
    {
        return $this->serve(Update::class, [
            'subscription' => $subscription,
            'end_at' => $request->get('end_at'),
            'source_contact_id' => $request->get('source_contact_id'),
        ]);
    }

    /**
     * @SWG\Delete(
     *     path="/contact/{identifier}/subscriptions/{subscription_id}",
     *     summary="Delete a contact subscription",
     *     tags={"contact:subscription"},
     *     description="Delete contact subscription.",
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
     *     },
     * )
     * @param Contact $contact
     * @param Subscription $subscription
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact, Subscription $subscription)
    {
        return $this->serve(Destroy::class, [
            'contact' => $contact,
            'subscription' => $subscription,
        ]);
    }

    /**
     * @SWG\Put(
     *     path="/contact/{identifier}/subscriptions/{subscription_id}/accept-terms",
     *     summary="Accept subscription terms.",
     *     tags={"contact:subscription"},
     *     description="Accept subscription terms.",
     *     operationId="contact-subscription-accept-terms",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Subscription"),
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
     * @param Contact $_
     * @param Subscription $subscription
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function acceptTerms(Request $request, Contact $_, Subscription $subscription)
    {
        return $this->serve(AcceptTerms::class, [
            'subscription' => $subscription,
            'term_id' => $request->get('term_id')
        ]);
    }
}
