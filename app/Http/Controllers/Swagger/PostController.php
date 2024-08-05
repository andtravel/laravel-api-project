<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Swagger\MainController;
use App\Http\Controllers\Swagger\Post;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="API Endpoints for posts"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/posts",
 *     summary="Get all posts",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *          name="page",
 *          in="query",
 *          description="Page number (by default = 1)",
 *          required=false,
 *          @OA\Schema(type="integer", default=1)
 *      ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(ref="#/components/schemas/Post")
 *             ),
 *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/v1/posts?page=1"),
 *             @OA\Property(property="from", type="integer", example=1),
 *             @OA\Property(property="last_page", type="integer", example=1),
 *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/v1/posts?page=1"),
 *             @OA\Property(property="next_page_url", type="string", example=null),
 *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/posts"),
 *             @OA\Property(property="links", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="url", type="string", example="http://localhost:8000/api/v1/posts?page=2"),
 *                     @OA\Property(property="label", type="string", example="1"),
 *                     @OA\Property(property="active", type="boolean", example=true),
 *                 ),
 *             ),
 *
 *             @OA\Property(property="per_page", type="integer", example=10),
 *             @OA\Property(property="prev_page_url", type="string", example=null),
 *             @OA\Property(property="to", type="integer", example=6),
 *             @OA\Property(property="total", type="integer", example=6),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Not found"
 *     ),
 * )
 *
 * @OA\Post(
 *     path="/api/v1/posts",
 *     summary="Create a new post",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "image", "category_id", "user_id"},
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="image", type="string", format="binary"),
 *                 @OA\Property(property="category_id", type="integer"),
 *                 @OA\Property(property="user_id", type="integer"),
 *                 example={"title": "Title", "image": "image.png", "category_id": 1, "user_id": 1}
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Post successful created",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", ref="#/components/schemas/Post"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *     ),
 *    @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Not found"
 *     ),
 * )
 *
 * @OA\Put(
 *     path="/api/v1/posts/{post}",
 *     summary="Update a post",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="post",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "image", "category_id", "user_id"},
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="image", type="string", format="binary"),
 *                 @OA\Property(property="category_id", type="integer"),
 *                 @OA\Property(property="user_id", type="integer"),
 *                 example={"title": "Title", "image": "image.png", "category_id": 1, "user_id": 1}
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Post successful updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", ref="#/components/schemas/Post"),
 *             example={"id": 1, "title": "Title", "image": "image.png", "category_id": 1, "user_id": 1, "created_at": "2024-01-01T00:00:00.000000Z", "updated_at": "2024-01-01T00:00:00.000000Z"}
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Not found"
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *     ),
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/posts/{post}",
 *     summary="Delete a post",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="post",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=204,
 *         description="Post successful deleted",
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Not found"
 *     ),
 * )
 *
 * @OA\Get(
 *     path="/api/v1/posts/employee/{employee}",
 *     summary="Get employee posts",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="employee",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *
 *     @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1),
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(ref="#/components/schemas/Post")
 *              ),
 *              @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/v1/posts?page=1"),
 *              @OA\Property(property="from", type="integer", example=1),
 *              @OA\Property(property="last_page", type="integer", example=1),
 *              @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/v1/posts?page=1"),
 *              @OA\Property(property="next_page_url", type="string", example=null),
 *              @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/posts"),
 *              @OA\Property(property="links", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="url", type="string", example="http://localhost:8000/api/v1/posts?page=2"),
 *                      @OA\Property(property="label", type="string", example="1"),
 *                      @OA\Property(property="active", type="boolean", example=true),
 *                  ),
 *              ),
 *
 *              @OA\Property(property="per_page", type="integer", example=10),
 *              @OA\Property(property="prev_page_url", type="string", example=null),
 *              @OA\Property(property="to", type="integer", example=6),
 *              @OA\Property(property="total", type="integer", example=6),
 *          ),
 *     ),
 *
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *
 *      @OA\Response(
 *          response=404,
 *          description="Not found"
 *      ),
 *
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *      ),
 *
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      ),
 * )
 *
 * @OA\Get(
 *     path="/api/v1/posts/category/{category}",
 *     summary="Get category posts",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="category",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(ref="#/components/schemas/Post")
 *             ),
 *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/v1/posts?page=1"),
 *             @OA\Property(property="from", type="integer", example=1),
 *             @OA\Property(property="last_page", type="integer", example=1),
 *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/v1/posts?page=1"),
 *             @OA\Property(property="next_page_url", type="string", example=null),
 *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/posts"),
 *             @OA\Property(property="links", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="url", type="string", example="http://localhost:8000/api/v1/posts?page=2"),
 *                     @OA\Property(property="label", type="string", example="1"),
 *                     @OA\Property(property="active", type="boolean", example=true),
 *                 ),
 *             ),
 *
 *             @OA\Property(property="per_page", type="integer", example=10),
 *             @OA\Property(property="prev_page_url", type="string", example=null),
 *             @OA\Property(property="to", type="integer", example=6),
 *             @OA\Property(property="total", type="integer", example=6),
 *         ),
 *    ),
 *
 *    @OA\Response(
 *        response=401,
 *        description="Unauthenticated",
 *    ),
 *
 *    @OA\Response(
 *        response=403,
 *        description="Forbidden"
 *    ),
 *
 *    @OA\Response(
 *        response=404,
 *        description="Not found"
 *    ),
 *
 *    @OA\Response(
 *        response=422,
 *        description="Validation error",
 *    ),
 *
 *    @OA\Response(
 *        response=500,
 *        description="Internal server error",
 *    ),
 * )
 */

class PostController extends MainController
{
    //
}
