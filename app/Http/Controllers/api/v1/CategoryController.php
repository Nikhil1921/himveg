<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CategoryManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get_categories()
    {
        try {
            $categories = Category::with(['childes.childes'])->where(['position' => 0])->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_products($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(Helpers::product_data_formatting(CategoryManager::products($id, $request['lat'], $request['lng']), true), 200);
    }
}
