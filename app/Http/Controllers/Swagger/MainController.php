<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Documentation for Laravel API project",
 *      description="API documentation for Laravel API project",
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 * )
 *
 * @OA\PathItem(
 *     path="/api/v1/",
 *     summary="Laravel API project"
 * )
 */

class MainController extends Controller
{

}
