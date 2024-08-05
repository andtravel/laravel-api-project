<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Post::class);

        $user = $request->user();
        $posts = $user->isManager()
            ? Post::whereIn('user_id', $user->employees->pluck('id'))->paginate(10)
            : $user->posts()->paginate(10);

        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        Gate::authorize('create', Post::class);

        $imagePath = $request->file('image')->store('posts', 'public');

        $post = Post::create([
            'title' => $request->title,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id
        ]);

        return response()->json(['data' => $post], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        Gate::authorize('update', $post);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($post->image);
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = $imagePath;
        }

        $post->title = $request->title;
        $post->category_id = $request->category_id;
        $post->save();

        return response()->json(['data' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        Storage::disk('public')->delete($post->image);

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 204);
    }

    public function byEmployee(Request $request, $employeeId)
    {
        Gate::authorize('viewAny', Post ::class);

        $user = $request->user();
        $posts = $user->isManager()
            ? Post::whereIn('user_id', $user->employees->pluck('id'))->where('user_id', $employeeId)->paginate(10)
            : $user->posts()->where('user_id', $employeeId)->paginate(10);

        return response()->json($posts);
    }

    public function byCategory(Category $category, Request $request)
    {
        Gate::authorize('viewAny', Post ::class);

        $user = $request->user();
        $posts = $user->isManager()
            ? Post::whereIn('user_id', $user->employees()->pluck('id'))->where('category_id', $category->id)->paginate(10)
            : $user->posts()->where('category_id', $category->id)->paginate(10);

        return response()->json($posts);
    }
}
