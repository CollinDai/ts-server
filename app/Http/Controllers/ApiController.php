<?php
/**
 * Created by PhpStorm.
 * User: Peike
 * Date: 8/25/2015
 * Time: 1:46 AM
 */
namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests;


/**
 * Class ApiController
 * @package app\Http\Controllers
 */
abstract class ApiController extends Controller
{
    protected $statusCode = 200;
    /**
     *
     */
    public function respond($data, $headers = []){
        return response()->json($data,$this->statusCode, $headers);
    }

    public function respondWithError($message) {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    public function respondNotFound($message = 'Not Found!'){
        $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
