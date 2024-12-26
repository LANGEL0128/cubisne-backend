<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     * @param  collection $result
     * @param  string $message
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $result
        ], $code);
    }

	/**
     * return error response.
     * @param  string $error
     * @param  array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = ['status' => 'Error','message' => $error,];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

	public function getStructurePaginate($o)
    {
        $pagination = [
            'current_page' => $o->currentPage(),
            'per_page' => $o->perPage(),
            'from' => $o->firstItem(),
            'to' => $o->lastItem(),
            'total' => $o->total(),
            'last_page' => $o->lastPage(),
            'path' => $o->path(),
            'first_page_url' => $o->url(1),
            'prev_page_url' => $o->previousPageUrl(),
            'next_page_url' => $o->nextPageUrl(),
            'last_page_url' => $o->url($o->lastPage()),
            'hasPages' => $o->hasPages(),
            'hasMorePages' => $o->hasMorePages()
        ];
        return $pagination;
    }

    protected function slug($string) {
        $characters = array(
            "Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u",
            "á" => "a", "ç" => "c", "é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u",
            "à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u"
            );
        $string = strtr($string, $characters);
        $string = strtolower(trim($string));
        $string = preg_replace("/[^a-z0-9-]/", "-", $string);
        $string = preg_replace("/-+/", "-", $string);
            if(substr($string, strlen($string) - 1, strlen($string)) === "-") {
                $string = substr($string, 0, strlen($string) - 1);
            }
        return $string.'-'.date('YmdHis');
    }
}
