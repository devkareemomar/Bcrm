<?php


namespace App\Services\Api;

use App\Classes\JsonResponse;

class BaseApiService
{
    private $jsonResponse;

    public function jsonResponse() : JsonResponse
    {
        if ($this->jsonResponse) {
            return $this->jsonResponse;
        }

        return $this->jsonResponse = new JsonResponse();
    }
}
