<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::select('id','name','email','role')
            ->latest()
            ->get();
    }


    public function updateRole(Request $request, $id)
    {
        // return $user;
        if ($request->user()->role !== 'admin') {
            return response()->json(['message'=>'Forbidden'],403);
        }

        $user = User::findOrFail($id);

        $user->role = $request->role;

        $user->save();

        return $user;
    }
}
