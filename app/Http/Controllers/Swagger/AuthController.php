<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Swagger\MainController;
use App\Http\Controllers\Swagger\User;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for authentication"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/register",
 *     summary="Register a new manager",
 *     tags={"Auth"},
 *
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  required={"name", "email", "password", "password_confirmation"},
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="email", type="string", format="email"),
 *                  @OA\Property(property="password", type="string", format="password"),
 *                  @OA\Property(property="password_confirmation", type="string", format="password"),
 *                  example={"name": "John Doe", "email": "jHrQH@example.com", "password": "password", "password_confirmation": "password"}
 *              ),
 *          ),
 *      ),
 *
 *      @OA\Response(
 *          response=201,
 *          description="Manager successful registered",
 *          @OA\JsonContent(
 *              @OA\Property(property="data", ref="#/components/schemas/Manager"),
 *              @OA\Property(property="access_token", type="string", default="1|YbIjgRmAhZNV6obF6lLqJ0lGOvoc1IxfiDeTIDHI124d1dfr"),
 *              @OA\Property(property="token_type", type="string", default="Bearer"),
 *          ),
 *      ),
 *
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *      )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/login",
 *     summary="Login a manager or employee",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  required={"email", "password"},
 *                  @OA\Property(property="email", type="string"),
 *                  @OA\Property(property="password", type="string"),
 *                  example={"email": "jHrQH@example.com", "password": "password"}
 *              ),
 *          ),
 *      ),
 *
 *      @OA\Response(
 *          response=200,
 *          description="User successful login",
 *          @OA\JsonContent(
 *               oneOf={
 *                   @OA\Schema(ref="#/components/schemas/Manager"),
 *                   @OA\Schema(ref="#/components/schemas/Employee"),
 *               },
 *               @OA\Property(property="access_token", type="string", default="1|YbIjgRmAhZNV6obF6lLqJ0lGOvoc1IxfiDeTIDHI124d1dfr"),
 *               @OA\Property(property="token_type", type="string", default="Bearer"),
 *         ),
 *      ),
 *
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/logout",
 *     summary="Logout a manager or employee",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Successful logout",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", default="Logout successful"),
 *         ),
 *     ),
 * )
 */

class AuthController extends MainController
{

}
