<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            //code...
            $lists = Product::all();
            Log::info('Lists Product: ', ['product' => $lists]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_response'),
                'data' => $lists
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error Product: ', ['product' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        //
        try {
            //code...
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price
            ]);
            Log::info('Store Product: ', ['product' => [
                'id' => $product->id,
                'name' => $request->name,
                'price' => $request->price
            ]]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_response'),
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error Store Product: ', ['product' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Product $product)
    {
        //
        try {
            //code...
            Log::info('Show Product: ', ['product' => $product]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_response'),
                'data' => $product
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error Show Product: ', ['product' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        //
        try {
            //code...
            $product->update([
                'name' => $request->name,
                'price' => $request->price
            ]);
            Log::info('Update Product: ', ['product' => [
                'id' => $product->id,
                'name' => $request->name,
                'price' => $request->price
            ]]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_response'),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error Update Product: ', ['product' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        try {
            //code...
            Log::info('Delete Product: ', ['product' => $product]);
            $product->delete();
            return response()->json([
                'success' => true,
                'message' => __('validation.success_response'),
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error Delete Product: ', ['product' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
