<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * title="Your super ApplicationAPI",
 * version="1.0.0",
 * )
 * @OA\Parameter(
 *      name="range",
 *      in="query",
 *      description="the range of items to be fetched e.g range[10, 20]",
 *      required=false,
 *      @OA\Schema(
 *          type="string",
 *      )
 * ),
 *  @OA\Parameter(
 *      in="query",
 *      name="sort",
 *      description="sorts items base of field provided",
 *      @OA\Examples(
 *          example = "-id", summary = "sorts in an descending manner by append a '-' ", value="-id"
 *      ),
 *      @OA\Examples(
 *          example = "id", summary = "sorts in an ascending manner", value="id"
 *      ),
 *      required=false,
 *  )
 *
 *  @OA\Parameter(
 *      name="filter",
 *      in="query",
 *      style="deepObject",
 *      description="filters base on the parameter passed e.g filter[field]=value",
 *      required=false,
 * ),
 *
 *  @OA\Parameter(
 *      name="id",
 *      description="ID of model to fetch",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *          type="integer",
 *          format="int64",
 *      )
 *  ),
 *
 * @OA\Response(
 *      response="404",
 *      description="not found",
 *      @OA\JsonContent(
 *           @OA\Property(property="message", type="string", example="")
 *       ),
 *  )
 *
 *
 *  @OA\Response(
 *      response=400,
 *      description="Error: Bad Request",
 *      @OA\JsonContent(
 *          @OA\Property(property="message", type="string", example="Requested filter(s) `description, are not allowed. Allowed filter(s) are `created_at` ")
 *      ),
 *  ),
 */
abstract class OpenApiController extends BaseController
{
}
