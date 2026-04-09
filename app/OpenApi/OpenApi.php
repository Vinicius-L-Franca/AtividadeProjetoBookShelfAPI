<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="BookShelf API",
 *     version="1.0.0",
 *     description="API REST para gerenciar uma estante de livros por gêneros."
 *   ),
 *   @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Servidor local"
 *   )
 * )
 */
final class OpenApi {}