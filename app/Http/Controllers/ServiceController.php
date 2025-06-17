<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $response = [
            'message' => 'Servicios recuperados correctamente',
            'error' => null,
            'data' => []
        ];
        $httpCode = Response::HTTP_OK;

        try {
            $services = Service::orderBy('position', 'desc')->with('category:id,name')->get();
            $response['data'] = $services;
        } catch (\Exception $e) {
            $response['message'] = 'Error al recuperar los servicios';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    public function store(Request $request)
    {
        $response = [
            'message' => 'Servicio creado correctamente',
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
            $response['message'] = 'Error de validacion';
            $response['error'] = $validator->errors();
            $httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json($response, $httpCode);
        }

        try {
            $service = Service::create($data);
            $response['data'] = $service;
        } catch (\Exception $e) {
            $response['message'] = 'Error al crear el servicio';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    public function show(string $id)
    {
        $response = [
            'message' => 'Servicio recuperado correctamente',
            'error' => null,
            'data' => []
        ];
        $httpCode = Response::HTTP_OK;

        try {
            $service = Service::find($id);

            if (!$service) {
                $response['message'] = 'Servicio no encontrado';
                $httpCode = Response::HTTP_NOT_FOUND;

                return response()->json($response, $httpCode);
            }

            $response['data'] = $service;
        } catch (\Exception $e) {
            $response['message'] = 'Error al recuperar el servicio';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    public function update(Request $request, string $id)
    {
        $response = [
            'message' => 'Servicio actualizado correctamente',
            'error' => null,
            'data' => []
        ];
        $data = $request->all();
        $httpCode = Response::HTTP_OK;

        $service = Service::find($id);

        if (!$service) {
            $response['message'] = 'Servicio no encontrado';
            $httpCode = Response::HTTP_NOT_FOUND;

            return response()->json($response, $httpCode);
        }

        $validator = Validator::make($data, [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:services,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'string|nullable',
            'position' => 'required|integer|min:0',
            'status' => 'required|in:enabled,disabled'
        ]);

        if ($validator->fails()) {
            $response['message'] = 'Error de validación';
            $response['error'] = $validator->errors();
            $httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json($response, $httpCode);
        }

        try {
            $service->update($data);
            $response['data'] = $service;
        } catch (\Exception $e) {
            $response['message'] = 'Error al actualizar el servicio';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }

    public function destroy(string $id)
    {
        $response = [
            'message' => 'Servicio eliminado correctamente',
            'error' => null
        ];
        $httpCode = Response::HTTP_NO_CONTENT;

        try {
            $service = Service::find($id);

            if (!$service) {
                $response['message'] = 'Servicio no encontrado';
                $httpCode = Response::HTTP_NOT_FOUND;

                return response()->json($response, $httpCode);
            }

            $service->delete();
            $httpCode = Response::HTTP_NO_CONTENT;
        } catch (\Exception $e) {
            $response['message'] = 'Error al eliminar el servicio';
            $response['error'] = $e->getMessage();
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($response, $httpCode);
    }
}
