<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Rede Sugar OpenApi",
 *      description="Rede Sugar Swagger OpenApi"
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8081/api",
 *      description="Ambiente local"
 *  )
 *
 * @OA\Server(
 *      url="https://redesugar-api-hom.weampp.com.br/api",
 *      description="Ambiente Homologação"
 *  )
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
