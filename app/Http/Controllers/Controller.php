<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function jsonResponse($success, $message, $data = null, $code = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function success($message = 'Operación exitosa', $data = null)
    {
        return $this->jsonResponse(true, $message, $data);
    }

    protected function error($message = 'Error en la operación', $code = 400)
    {
        return $this->jsonResponse(false, $message, null, $code);
    }
}
