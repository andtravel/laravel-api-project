<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_see_his_posts(): void
    {
        $employee = User::factory()->employee()->create();

        $category = Category::factory()->create();
        Post::factory(3)->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($employee)->getJson('api/v1/posts');

        $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'image',
                    'category_id',
                    'user_id'
                ]
            ]
        ]);

        $response->assertJsonCount(3, 'data');
    }

    public function test_employee_cannot_see_other_employees_posts()
    {
        $employee1 = User::factory()->employee()->create();
        $employee2 = User::factory()->employee()->create();
        $category = Category::factory()->create();
        $posts = Post::factory(3)->create([
            'category_id' => $category->id,
            'user_id' => $employee1->id
        ]);

        $response = $this->actingAs($employee2)->getJson('api/v1/posts');

        $response->assertForbidden();
        $response->assertJsonMissing($posts->toArray());
    }

    public function test_employee_can_see_his_own_posts_by_category()
    {
        $employee = User::factory()->employee()->create();
        $category = Category::factory()->create();
        $posts =Post::factory(3)->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($employee)->getJson('api/v1/posts/category/' . $category->id);

        $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'image',
                    'category_id',
                    'user_id'
                ]
            ]
        ]);

        $response->assertJsonCount(3, 'data');
    }

    public function test_employee_can_not_see_his_own_posts_by_other_category()
    {
        $employee = User::factory()->employee()->create();
        $categories = Category::factory(2)->create();
        $posts =Post::factory()->create([
            'category_id' => $categories[0]->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($employee)->getJson('api/v1/posts/category/' . $categories[1]->id);

        $response->assertJsonMissing($posts->toArray());
    }

    public function test_manager_can_see_posts_of_his_employees_by_category()
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create(
            ['manager_id' => $manager->id]
        );
        $category = Category::factory()->create();
        $posts = Post::factory(3)->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($manager)->getJson('api/v1/posts/category/' . $category->id);

        $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'image',
                    'category_id',
                    'user_id'
                ]
            ]
        ]);

        $response->assertJsonCount(3, 'data');
    }

    public function test_manager_can_not_see_posts_of_his_employees_by_other_category()
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create(
            ['manager_id' => $manager->id]
        );
        $categories = Category::factory(2)->create();
        $posts = Post::factory(3)->create([
            'category_id' => $categories[0]->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($manager)->getJson('api/v1/posts/category/' . $categories[1]->id);

        $response->assertJsonMissing($posts->toArray());
    }

    public function test_manager_can_see_posts_by_his_employee()
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create(
            ['manager_id' => $manager->id]
        );
        $category = Category::factory()->create();
        Post::factory(3)->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($manager)->getJson('api/v1/posts/employee/' . $employee->id);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'image',
                        'category_id',
                        'user_id'
                    ]
                ]
            ]);

        $response->assertJsonCount(3, 'data');
    }

    public function test_manager_can_see_his_employees_posts()
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create(
            ['manager_id' => $manager->id]
        );
        $category = Category::factory()->create();
        Post::factory(3)->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($manager)->getJson('api/v1/posts');

        $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'image',
                    'category_id',
                    'user_id'
                ]
            ]
        ]);

        $response->assertJsonCount(3, 'data');
    }

    public function test_manager_can_not_see_other_employees_posts()
    {
        $manager1 = User::factory()->manager()->create();
        $manager2 = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create(
            ['manager_id' => $manager1->id]
        );
        $category = Category::factory()->create();
        $posts = Post::factory(3)->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response = $this->actingAs($manager2)->getJson('api/v1/posts');

        $response->assertForbidden();
        $response->assertJsonMissing($posts->toArray());
    }

    public function test_employee_can_create_post(): void
    {
        $employee = User::factory()->employee()->create();
        $category = Category::factory()->create();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($employee)->postJson('api/v1/posts', [
            'title' => 'Test title',
            'image' => $file,
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure([
                'data' => ['id', 'title', 'image', 'user_id', 'category_id']
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test title'
        ]);

        Storage::disk('public')->assertExists('posts/' . $file->hashName());
    }

    public function test_employee_can_update_post()
    {
        $employee = User::factory()->employee()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($employee)->putJson('api/v1/posts/' . $post->id, [
            'title' => 'Test title update',
            'image' => $file,
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
                'data' => ['id', 'title', 'image', 'user_id', 'category_id']
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test title update'
        ]);

        Storage::disk('public')->assertExists('posts/' . $file->hashName());
    }

    public function test_employee_can_delete_post()
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create([
            'manager_id' => $manager->id
            ]);
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);

        $response = $this->actingAs($employee)->deleteJson('api/v1/posts/' . $post->id);

        $response->assertNoContent();
    }

    public function test_other_employee_cannot_delete_post()
    {
        $manager = User::factory()->manager()->create();
        $employee1 = User::factory()->employee()->create([
            'manager_id' => $manager->id
        ]);
        $employee2 = User::factory()->employee()->create([
            'manager_id' => $manager->id
        ]);
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $employee1->id
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);

        $response = $this->actingAs($employee2)->deleteJson('api/v1/posts/' . $post->id);

        $response->assertForbidden();
    }

    public function test_manager_of_employee_can_delete_post()
    {
        $manager = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create([
            'manager_id' => $manager->id
        ]);
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);

        $response = $this->actingAs($manager)->deleteJson('api/v1/posts/' . $post->id);

        $response->assertNoContent();
    }

    public function test_another_manager_cannot_delete_post()
    {
        $manager1 = User::factory()->manager()->create();
        $manager2 = User::factory()->manager()->create();
        $employee = User::factory()->employee()->create([
            'manager_id' => $manager1->id
        ]);
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $employee->id
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);

        $response = $this->actingAs($manager2)->deleteJson('api/v1/posts/' . $post->id);

        $response->assertForbidden();
    }
}
