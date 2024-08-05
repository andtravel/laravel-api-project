<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Swagger\MainController;

/**
 * @OA\Schema(
 *     schema="Post",
 *     required={"id","title","image","category_id","user_id","created_at","updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="image", type="string"),
 *     @OA\Property(property="category_id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     example={"id": 1, "title": "Title", "image": "image.png", "category_id": 1, "user_id": 1, "created_at": "2024-01-01T00:00:00.000000Z", "updated_at": "2024-01-01T00:00:00.000000Z"}
 * )
 */

class Post extends MainController
{

}
