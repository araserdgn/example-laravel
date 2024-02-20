<?php

if (!function_exists('apiResponse')) {
    function apiResponse($message = null, $status =200, $data=null) {
        return response()->json(['message '=>$message, 'data' =>$data], $status);
    }
}
