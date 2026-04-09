<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Livros",
 *   description="Operações relacionadas a livros."
 * )
 *
 * @OA\Schema(
 *   schema="Book",
 *   type="object",
 *   required={"id","genre_id","title","author","status","created_at","updated_at"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="genre_id", type="integer", example=1),
 *   @OA\Property(property="title", type="string", example="1984"),
 *   @OA\Property(property="author", type="string", example="George Orwell"),
 *   @OA\Property(property="pages", type="integer", nullable=true, example=328),
 *   @OA\Property(
 *     property="status",
 *     type="string",
 *     enum={"to_read","reading","finished"},
 *     example="to_read"
 *   ),
 *   @OA\Property(property="rating", type="integer", nullable=true, minimum=1, maximum=5, example=5),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2026-04-07T14:09:44.000000Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2026-04-07T14:09:44.000000Z"),
 *   @OA\Property(property="genre", ref="#/components/schemas/Genre", nullable=true)
 * )
 */
class BookController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/books",
     *   summary="Listar livros",
     *   description="Retorna todos os livros cadastrados. Inclui o gênero relacionado (campo 'genre').",
     *   tags={"Livros"},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de livros",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Book")
     *     )
     *   )
     * )
     */
    public function index()
    {
        return Book::query()
            ->with('genre')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * @OA\Post(
     *   path="/api/books",
     *   summary="Criar livro",
     *   description="Cria um novo livro.",
     *   tags={"Livros"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"genre_id","title","author","status"},
     *       @OA\Property(property="genre_id", type="integer", example=1),
     *       @OA\Property(property="title", type="string", maxLength=255, example="Clean Code"),
     *       @OA\Property(property="author", type="string", maxLength=255, example="Robert C. Martin"),
     *       @OA\Property(property="pages", type="integer", nullable=true, example=464),
     *       @OA\Property(property="status", type="string", enum={"to_read","reading","finished"}, example="reading"),
     *       @OA\Property(property="rating", type="integer", nullable=true, minimum=1, maximum=5, example=5)
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Livro criado",
     *     @OA\JsonContent(ref="#/components/schemas/Book")
     *   ),
     *   @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'genre_id' => ['required', 'integer', 'exists:genres,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:to_read,reading,finished'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        return Book::create($data)->load('genre');
    }

    /**
     * @OA\Get(
     *   path="/api/books/{id}",
     *   summary="Exibir livro",
     *   description="Retorna um livro específico pelo ID. Inclui o gênero relacionado (campo 'genre').",
     *   tags={"Livros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do livro",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Livro encontrado",
     *     @OA\JsonContent(ref="#/components/schemas/Book")
     *   ),
     *   @OA\Response(response=404, description="Livro não encontrado")
     * )
     */
    public function show(Book $book)
    {
        return $book->load('genre');
    }

    /**
     * @OA\Put(
     *   path="/api/books/{id}",
     *   summary="Atualizar livro",
     *   description="Atualiza um livro existente pelo ID.",
     *   tags={"Livros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do livro",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="genre_id", type="integer", example=1),
     *       @OA\Property(property="title", type="string", maxLength=255, example="Clean Code"),
     *       @OA\Property(property="author", type="string", maxLength=255, example="Robert C. Martin"),
     *       @OA\Property(property="pages", type="integer", nullable=true, example=464),
     *       @OA\Property(property="status", type="string", enum={"to_read","reading","finished"}, example="finished"),
     *       @OA\Property(property="rating", type="integer", nullable=true, minimum=1, maximum=5, example=5)
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Livro atualizado",
     *     @OA\JsonContent(ref="#/components/schemas/Book")
     *   ),
     *   @OA\Response(response=404, description="Livro não encontrado"),
     *   @OA\Response(response=422, description="Erro de validação")
     * )
     *
     * @OA\Patch(
     *   path="/api/books/{id}",
     *   summary="Atualizar parcialmente livro",
     *   description="Atualiza parcialmente um livro existente pelo ID.",
     *   tags={"Livros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do livro",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="genre_id", type="integer", example=1),
     *       @OA\Property(property="title", type="string", maxLength=255, example="Clean Code"),
     *       @OA\Property(property="author", type="string", maxLength=255, example="Robert C. Martin"),
     *       @OA\Property(property="pages", type="integer", nullable=true, example=464),
     *       @OA\Property(property="status", type="string", enum={"to_read","reading","finished"}, example="finished"),
     *       @OA\Property(property="rating", type="integer", nullable=true, minimum=1, maximum=5, example=5)
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Livro atualizado",
     *     @OA\JsonContent(ref="#/components/schemas/Book")
     *   ),
     *   @OA\Response(response=404, description="Livro não encontrado"),
     *   @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'genre_id' => ['sometimes', 'required', 'integer', 'exists:genres,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'author' => ['sometimes', 'required', 'string', 'max:255'],
            'pages' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'status' => ['sometimes', 'required', 'in:to_read,reading,finished'],
            'rating' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $book->update($data);

        return $book->load('genre');
    }

    /**
     * @OA\Delete(
     *   path="/api/books/{id}",
     *   summary="Excluir livro",
     *   description="Exclui um livro pelo ID.",
     *   tags={"Livros"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do livro",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(response=204, description="Livro excluído"),
     *   @OA\Response(response=404, description="Livro não encontrado")
     * )
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}