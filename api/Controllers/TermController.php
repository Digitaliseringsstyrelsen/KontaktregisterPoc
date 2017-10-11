<?php

namespace Api\Controllers;

use Api\Features\Terms\Get;
use Api\Features\Terms\Index;
use Api\Maps\TermMap;
use Api\Models\Term;

class TermController extends BaseController implements ControllerInterface
{
    public function mapping()
    {
        return new TermMap();
    }

    /**
     * @SWG\Get(
     *     path="/terms",
     *     summary="Get terms",
     *     tags={"term"},
     *     description="Get terms.",
     *     operationId="terms",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Term"),
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

    /**
     * @SWG\Get(
     *     path="/terms/{term_id}",
     *     summary="Get term",
     *     tags={"term"},
     *     description="Get term.",
     *     operationId="term",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Items(ref="#/definitions/Term"),
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
     * @param Term $term
     * @return \Api\Support\Paginator|\Illuminate\Http\JsonResponse
     */
    public function show(Term $term)
    {
        return $this->serve(Get::class, [
            'term' => $term,
        ]);
    }
}
