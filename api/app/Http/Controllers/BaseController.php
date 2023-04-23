<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function successResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorResponse($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages))
        {
            $response['data'] = $errorMessages;
        }
        
        return response()->json($response, $code);
    }
}
