<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoredProcedureController extends Controller
{
    function showCategoryByName($name)
    {
        $response = [
            'message' => 'Proceso exitoso',
            'error' => null,
            'data' => []
        ];
        $httpCode = Response::HTTP_OK;

        try {
            $categry = DB::select('CALL getCategoryByName(?)', [$name]);

            if (empty($categry)) {
                $response['error'] = 'Categoría no encontrada.';
                $response['message'] = 'No se encontraron resultados para la categoría solicitada.'; 
                $httpCode = Response::HTTP_NOT_FOUND;
            }else
            {
                $response['data'] = $categry[0];
            }
        } catch (\Exception $e) {
            $response['error'] = 'Error al recuperar la categoría.';
            $response['message'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    function showCategoryProducts($catId)
    {
        $response = [
            'message' => 'Proceso exitoso',
            'error' => null,
            'data' => []
        ];
        $httpCode = Response::HTTP_OK;

        try {
            $products = DB::select('CALL GetProductsInCategory(?)', [$catId]);

            if (empty($products)) {
                $response['error'] = 'No hay productos en esta categoría.';
                $response['message'] = 'No se encontraron productos para la categoría solicitada.';
                $httpCode = Response::HTTP_NOT_FOUND;
            }else
            {
                $response['data'] = $products;
            }
        } catch (\Exception $e) {
            $response['error'] = 'Error al recuperar los productos de la categoría.';
            $response['message'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    function updateProductPriceSp($productId, Request $request)
    {
        $response = [
            'message' => 'Actualización exitosa',
            'error' => null,
            'data' => []
        ];
        $httpCode = Response::HTTP_OK;
        $validator = Validator::make($request->all(), [
            'new_price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/', 
        ]);

        if ($validator->fails()) {
            $response['message'] = 'Los datos proporcionados no son válidos.';
            $response['error'] = $validator->errors();

            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $newPrice = $request->input('new_price');

            DB::update('CALL UpdateProductPrice(?, ?)', [$productId, $newPrice]);
        } catch (\Exception $e) {
            $response['message'] = 'Error al actualizar el precio del producto.';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    function getServicesSP()
    {
        $response = [
            'message' => 'Servicios recuperados correctamente con SP',
            'error' => null,
            'data' => []
        ];
        $httpCode = Response::HTTP_OK;

        try {
            $services = DB::select('CALL GetAllServices()');
            $response['data'] = $services;
        } catch (\Exception $e) {
            $response['message'] = 'Error al recuperar los servicios con SP';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    function createServicesSp(Request $request)
    {
        $response = [
            'message' => 'Servicio creado correctamente con SP',
            'error' => null,
            'data' => []
        ];
        $data = $request->all();
        $httpCode = Response::HTTP_CREATED;

        $validator = Validator::make($data, [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'string|nullable',
            'position' => 'required|integer|min:0',
            'status' => 'required|in:enabled,disabled'
        ]);

        if ($validator->fails()) {
            $response['message'] = 'Error de validacion en SP';
            $response['error'] = $validator->errors();
            $httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json($response, $httpCode);
        }

        try {
            DB::statement('CALL CreateService(?, ?, ?, ?, ?, ?, ?)', [
                $data['category_id'],
                $data['name'],
                $data['description'] ?? '',
                $data['price'],
                $data['duration'] ?? '',
                $data['position'],
                $data['status']
            ]);
        } catch (\Exception $e) {
            $response['message'] = 'Error al crear el servicio en SP';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }
}
