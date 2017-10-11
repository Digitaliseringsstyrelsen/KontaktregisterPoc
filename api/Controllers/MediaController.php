<?php

namespace Api\Controllers;

use Api\Features\Media\Create;
use Api\Features\Media\Destroy;
use Api\Features\Media\Index;
use Api\Features\Media\Update;
use Api\Maps\MediaMap;
use Api\Models\Contact;
use Api\Models\Media;
use Illuminate\Http\Request;

class MediaController extends BaseController implements ControllerInterface
{
    public function mapping()
    {
        return new MediaMap();
    }

    /**
     * @SWG\Get(
     *     path="/contact/{identifier}/medias",
     *     summary="Get contact medias",
     *     tags={"contact:media"},
     *     description="Get contact medias.",
     *     operationId="contact-medias",
     *     produces={"application/json"},
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
     * @param Contact $contact
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function index(Contact $contact)
    {
        return $this->serve(Index::class, [
            'contact' => $contact,
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/contact/{identifier}/medias",
     *     summary="Create a contact media",
     *     tags={"contact:media"},
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
    public function store(Request $request, Contact $contact)
    {
        return $this->serve(Create::class, [
            'contact' => $contact,
            'type' => $request->get('type'),
            'data' => $request->get('data'),
        ]);
    }

    /**
     * @SWG\Delete(
     *     path="/contact/{identifier}/medias/{media_id}",
     *     summary="Delete a contact media",
     *     tags={"contact:media"},
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
     * @param Media $media
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact, Media $media)
    {
        return $this->serve(Destroy::class, [
            'contact' => $contact,
            'media' => $media,
        ]);
    }

    /**
     * @SWG\Put(
     *     path="/contact/{identifier}/medias/{media_id}/",
     *     summary="Update a contact media",
     *     tags={"contact:media"},
     *     description="Update contact media.",
     *     operationId="contact-medias-update",
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized",
     *      @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Media"),
     *         ),
     *     ),
     *     security={
     *         {
     *             "nemId": {"write:contact", "read:contact"},
     *             "basic-auth": {"read:contact"}
     *         }
     *     }
     * )
     * @param Contact $contact
     * @param Media $media
     * @param Request $request
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function update(Contact $contact, Media $media, Request $request)
    {
        return $this->serve(Update::class, [
            'contact' => $contact,
            'media'   => $media,
            'type'    => $request->get('type'),
            'data'    => $request->get('data'),
        ]);
    }
}
