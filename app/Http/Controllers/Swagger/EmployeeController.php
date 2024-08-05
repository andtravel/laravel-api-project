<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Swagger\MainController;
use App\Http\Controllers\Swagger\User;

/**
 * @OA\Tag(
 *     name="Employees",
 *     description="API Endpoints for employees"
 * )
 *     @OA\Post(
 *      path="/api/v1/employees",
 *      summary="Create a new employee",
 *      tags={"Employees"},
 *      security={{"bearerAuth":{}}},
 *
 *       @OA\RequestBody(
 *           @OA\MediaType(
 *               mediaType="application/json",
 *               @OA\Schema(
 *                   required={"name", "email", "password", "password_confirmation"},
 *                   @OA\Property(property="name", type="string"),
 *                   @OA\Property(property="email", type="string", format="email"),
 *                   @OA\Property(property="password", type="string", format="password"),
 *                   @OA\Property(property="password_confirmation", type="string", format="password"),
 *                   example={"name": "Bill Corney", "email": "corney@example.com", "password": "password", "password_confirmation": "password"}
 *               ),
 *           ),
 *       ),
 *
 *       @OA\Response(
 *           response=201,
 *           description="Employee successful registered",
 *           @OA\JsonContent(
 *               @OA\Property(property="data", ref="#/components/schemas/Employee"),
 *               @OA\Property(property="access_token", type="string", default="1|YbIjgRmAhZNV6obF6lLqJ0lGOvoc1IxfiDeTIDHI124d1dfr"),
 *               @OA\Property(property="token_type", type="string", default="Bearer"),
 *           ),
 *       ),
 *
 *       @OA\Response(
 *           response=422,
 *           description="Validation error",
 *       )
 *  )
 */

class EmployeeController extends MainController
{
    //
}
