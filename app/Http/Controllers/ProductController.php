<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response; // Para usar constantes de códigos de respuesta HTTP
use Illuminate\Support\Facades\Validator; // Para facilitar la validación
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('category')->get();
            return response()->json([
                'message' => 'Productos recuperados correctamente',
                'data' => $products
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar productos',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'position' => 'nullable|integer',
            'status' => 'required|in:enabled,disabled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product = Product::create($request->all());
            return response()->json([
                'message' => 'Productos creados correptamente',
                'data' => $product
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function  show(string $id)
    {
        try {
            $product = Product::with('category')->find($id);
            if (!$product) {
                return response()->json([
                    'message' => 'Productos no enconrtrado'
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json([
                'message' => 'Productos recuperado correptamente',
                'data' => $product
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'recuperar  producto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, string $id)
    {

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Productos no enconrtrado'
            ], Response::HTTP_NOT_FOUND);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'position' => 'nullable|integer',
            'status' => 'required|in:enabled,disabled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product->update($request->all());
            return response()->json([
                'message' => 'Productos actualizado correptamente',
                'data' => $product
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar producto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            $product->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar producto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
