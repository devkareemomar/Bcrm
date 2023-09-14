<?php

namespace App\Http\Controllers\Api;

use App\Classes\JsonResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    private $jsonResponse;

    public function jsonResponse(): JsonResponse
    {
        if ($this->jsonResponse) {
            return $this->jsonResponse;
        }

        return $this->jsonResponse = new JsonResponse();
    }
}
