<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Gêneros",
 *   description="Operações relacionadas a gêneros literários."
 * )
 *
 * @OA\Schema(
 *   schema="Genre",
 *   type="object",
 *   required={"id","name","created_at","updated_at"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="Ficção"),
 *   @OA\Property(property="description", type="string", nullable=true, example="Romances e histórias fictícias"),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2026-04-07T14:09:44.000000Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2026-04-07T14:09:44.000000Z")
 * )
 */
class GenreController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/genres",
     *   summary="Listar gêneros",
     *   description="Retorna todos os gêneros cadastrados.",
     *   tags={"Gêneros"},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de gêneros",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Genre")
     *     )
     *   )
     * )
     */
    public function index()
    {
        return Genre::query()->orderBy('name')->get();
    }

    /**
     * @OA\Post(
     *   path="/api/genres",
     *   summary="Criar gênero",
     *   description="Cria um novo gênero.",
     *   tags={"Gêneros"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", maxLength=255, example="Ficção"),
     *       @OA\Property(property="description", type="string", nullable=true, example="Romances e histórias fictícias")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gênero criado",
     *     @OA\JsonContent(ref="#/components/schemas/Genre")
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Erro de validação"
     *   )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        return Genre::create($data);
    }

    /**
     * @OA\Get(
     *   path="/api/genres/{id}",
     *   summary="Exibir gênero",
     *   description="Retorna um gênero específico pelo ID.",
     *   tags={"Gêneros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do gênero",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gênero encontrado",
     *     @OA\JsonContent(ref="#/components/schemas/Genre")
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Gênero não encontrado"
     *   )
     * )
     */
    public function show(Genre $genre)
    {
        return $genre;
    }

    /**
     * @OA\Put(
     *   path="/api/genres/{id}",
     *   summary="Atualizar gênero",
     *   description="Atualiza um gênero existente pelo ID.",
     *   tags={"Gêneros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do gênero",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string", maxLength=255, example="Ficção"),
     *       @OA\Property(property="description", type="string", nullable=true, example="Descrição atualizada")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gênero atualizado",
     *     @OA\JsonContent(ref="#/components/schemas/Genre")
     *   ),
     *   @OA\Response(response=404, description="Gênero não encontrado"),
     *   @OA\Response(response=422, description="Erro de validação")
     * )
     *
     * @OA\Patch(
     *   path="/api/genres/{id}",
     *   summary="Atualizar parcialmente gênero",
     *   description="Atualiza parcialmente um gênero existente pelo ID.",
     *   tags={"Gêneros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do gênero",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string", maxLength=255, example="Ficção"),
     *       @OA\Property(property="description", type="string", nullable=true, example="Descrição atualizada")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gênero atualizado",
     *     @OA\JsonContent(ref="#/components/schemas/Genre")
     *   ),
     *   @OA\Response(response=404, description="Gênero não encontrado"),
     *   @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        $genre->update($data);

        return $genre;
    }

    /**
     * @OA\Delete(
     *   path="/api/genres/{id}",
     *   summary="Excluir gênero",
     *   description="Exclui um gênero pelo ID.",
     *   tags={"Gêneros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do gênero",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=204,
     *     description="Gênero excluído"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Gênero não encontrado"
     *   )
     * )
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return response()->noContent();
    }
}