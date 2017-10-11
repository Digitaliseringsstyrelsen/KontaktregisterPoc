<?php

namespace Api\Controllers;

use Api\Features\Contacts\Create;
use Api\Features\Contacts\Get;
use Api\Features\Contacts\Log;
use Api\Features\Contacts\Register;
use Api\Features\Contacts\Search;
use Api\Maps\ContactMap;
use Api\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends BaseController implements ControllerInterface
{
    public function mapping()
    {
        return new ContactMap();
    }

    /**
     * @SWG\Get(
     *     path="/contact/{identifier}",
     *     summary="Get contact by identifier, either CPR or CVR",
     *     tags={"contact"},
     *     description="Return contact.",
     *     operationId="contact",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Contact")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Invalid tag value",
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
    public function show(Contact $contact)
    {
        return $this->serve(Get::class, [
            'contact' => $contact,
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/contact",
     *     summary="Create contact.",
     *     tags={"contact"},
     *     description="Create a contact.",
     *     operationId="contact-create",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Contact")
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
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->serve(Create::class, [
            'type'       => $request->get('type'),
            'identifier' => $request->get('identifier'),
        ]);
    }

    /**
     * @SWG\Get(
     *     path="/search",
     *     summary="Get contact by identifier, either CPR or CVR",
     *     tags={"contact"},
     *     description="Return list of contacts.",
     *     operationId="search",
     *     produces={"application/json"},
     *    @SWG\Parameter(
     *      name="filter",
     *      in="query",
     *      description="Filter for subscription types.",
     *      required=false,
     *      enum={"sms", "notification"},
     *      type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized",
     *     ),
     *     security={
     *         {
     *             "basic-auth": {"read:contact"}
     *         }
     *     }
     * )
     * @param Request $request
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        return $this->serve(Search::class, [
            'query' => $request->get('query'),
            'filters' => $request->get('filters'),
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/contact/register",
     *     summary="Create contact resource.",
     *     tags={"contact"},
     *     description="Register a contact including media and subscription values.",
     *     operationId="contact-register",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=201,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Contact")
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
     *         },
     *     }
     * )
     * @param Request $request
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        return $this->serve(Register::class, [
            'contact'      => $request->get('contact'),
            'subscription' => $request->get('subscription'),
            'media'        => $request->get('media'),
        ]);
    }

    /**
     * @SWG\Get(
     *     path="/contact/{identifier}/logs",
     *     summary="Get contact logs.",
     *     tags={"contact"},
     *     description="Get contact logs.",
     *     operationId="contact-logs",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/contact")
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
    public function log(Request $request, Contact $contact)
    {
        return $this->serve(Log::class, [
            'contact' => $contact,
            'start' => $request->get('start'),
            'end' => $request->get('end'),
            'type' => $request->get('type'),
        ]);
    }
}
