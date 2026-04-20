<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryAdminController extends Controller
{

    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message'=>'Forbidden'],403);
        }

        return Category::all();
    }



    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message'=>'Forbidden'],403);
        }

        return Category::create([
            'name' => $request->name
        ]);
    }



    public function destroy(Request $request, Category $category)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message'=>'Forbidden'],403);
        }

        $category->delete();

        return response()->json([
            'message'=>'deleted'
        ]);
    }

}