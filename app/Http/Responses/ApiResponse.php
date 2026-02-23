<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public static function success(mixed $data = null, string $message = '', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            // Si es un Resource o ResourceCollection, lo transformamos
            if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
                return $data->additional(['success' => true, 'message' => $message])
                    ->response()
                    ->setStatusCode($code);
            }
            
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param mixed $errors
     * @param int $code
     * @return JsonResponse
     */
    public static function error(string $message, mixed $errors = null, int $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a created response (201).
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created(mixed $data = null, string $message = 'Recurso creado exitosamente'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Return a no content response (204).
     *
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return an unauthorized response (401).
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'No autorizado'): JsonResponse
    {
        return self::error($message, null, 401);
    }

    /**
     * Return a forbidden response (403).
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Acceso prohibido'): JsonResponse
    {
        return self::error($message, null, 403);
    }

    /**
     * Return a not found response (404).
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return self::error($message, null, 404);
    }

    /**
     * Return a validation error response (422).
     *
     * @param mixed $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function validationError(mixed $errors, string $message = 'Error de validación'): JsonResponse
    {
        return self::error($message, $errors, 422);
    }

    /**
     * Return a server error response (500).
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Error interno del servidor'): JsonResponse
    {
        return self::error($message, null, 500);
    }
}
