<?php

namespace App\Http\Controllers; // Se recomienda usar un namespace 'Api' para controladores de API

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Para usar constantes de códigos de respuesta HTTP
use Illuminate\Support\Facades\Validator; // Para facilitar la validación

// Este controlador manejará las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
// para el recurso de Categorías en tu API REST.
class CategoryController extends Controller
{
    /**
     * Muestra una lista de todas las categorías.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Obtener todas las categorías de la base de datos
            $categories = Category::all();

            // Devolver una respuesta JSON con las categorías y un mensaje de éxito
            return response()->json([
                'message' => 'Categorías recuperadas correctamente',
                'data' => $categories
            ], Response::HTTP_OK); // Código de estado HTTP 200 OK
        } catch (\Exception $e) {
            // En caso de error, devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'message' => 'Error al recuperar las categorías',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // Código de estado HTTP 500 Internal Server Error
        }
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name', // 'required' para el nombre, único en la tabla 'categories'
            'description' => 'nullable|string', // 'description' es opcional y debe ser una cadena
        ]);

        // Si la validación falla, devolver un error JSON
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // Código de estado HTTP 422 Unprocessable Entity
        }

        try {
            // Crear una nueva categoría con los datos validados
            $category = Category::create($request->all());

            // Devolver una respuesta JSON con la nueva categoría creada y un mensaje de éxito
            return response()->json([
                'message' => 'Categoría creada correctamente',
                'data' => $category
            ], Response::HTTP_CREATED); // Código de estado HTTP 201 Created
        } catch (\Exception $e) {
            // En caso de error, devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'message' => 'Error al crear la categoría',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // Código de estado HTTP 500 Internal Server Error
        }
    }

    /**
     * Muestra una categoría específica por su ID.
     *
     * @param  string  $id El ID de la categoría a mostrar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            // Buscar la categoría por su ID
            $category = Category::find($id);

            // Si la categoría no se encuentra, devolver un error 404
            if (!$category) {
                return response()->json([
                    'message' => 'Categoría no encontrada'
                ], Response::HTTP_NOT_FOUND); // Código de estado HTTP 404 Not Found
            }

            // Devolver la categoría encontrada y un mensaje de éxito
            return response()->json([
                'message' => 'Categoría recuperada correctamente',
                'data' => $category
            ], Response::HTTP_OK); // Código de estado HTTP 200 OK
        } catch (\Exception $e) {
            // En caso de error, devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'message' => 'Error al recuperar la categoría',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // Código de estado HTTP 500 Internal Server Error
        }
    }

    /**
     * Actualiza una categoría existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id El ID de la categoría a actualizar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        // Buscar la categoría por su ID
        $category = Category::find($id);

        // Si la categoría no se encuentra, devolver un error 404
        if (!$category) {
            return response()->json([
                'message' => 'Categoría no encontrada'
            ], Response::HTTP_NOT_FOUND); // Código de estado HTTP 404 Not Found
        }

        // Validar los datos de la solicitud
        // La regla 'unique' ignora el ID actual para evitar conflictos al actualizar el mismo registro
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        // Si la validación falla, devolver un error JSON
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // Código de estado HTTP 422 Unprocessable Entity
        }

        try {
            // Actualizar la categoría con los datos validados
            $category->update($request->all());

            // Devolver la categoría actualizada y un mensaje de éxito
            return response()->json([
                'message' => 'Categoría actualizada correctamente',
                'data' => $category
            ], Response::HTTP_OK); // Código de estado HTTP 200 OK
        } catch (\Exception $e) {
            // En caso de error, devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'message' => 'Error al actualizar la categoría',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // Código de estado HTTP 500 Internal Server Error
        }
    }

    /**
     * Elimina una categoría de la base de datos.
     *
     * @param  string  $id El ID de la categoría a eliminar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            // Buscar la categoría por su ID
            $category = Category::find($id);

            // Si la categoría no se encuentra, devolver un error 404
            if (!$category) {
                return response()->json([
                    'message' => 'Categoría no encontrada'
                ], Response::HTTP_NOT_FOUND); // Código de estado HTTP 404 Not Found
            }

            // Eliminar la categoría
            $category->delete();

            // Devolver una respuesta sin contenido (204) para indicar éxito sin datos de retorno
            return response()->json(null, Response::HTTP_NO_CONTENT); // Código de estado HTTP 204 No Content
        } catch (\Exception $e) {
            // En caso de error, devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'message' => 'Error al eliminar la categoría',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // Código de estado HTTP 500 Internal Server Error
        }
    }
}
