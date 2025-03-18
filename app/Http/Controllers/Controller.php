<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API de Gestión de Tareas y Usuarios",
 *     version="1.0.0",
 *     description="API para gestionar tareas y usuarios, incluyendo autenticación y operaciones CRUD.",
 *     @OA\Contact(
 *         email="lfvaldi@gmail.com",
 *         name="Luis Fernando - Fullstack Developer"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor de Desarrollo"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación mediante token JWT"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
