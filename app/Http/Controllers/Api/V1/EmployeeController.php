<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
   /* public function index()
    {
        $employees = User::where('manager_id', Auth::user()->id)->get();
        return response()->json($employees);
    }*/
    public function store(StoreEmployeeRequest $request)
    {
        Gate::authorize('create', User::class);

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'employee')->first()->id,
            'manager_id' => auth()->user()->id
        ]);

        return response()->json([
            'employee' => $employee
        ], 201);
    }
}
