<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Book::truncate();

        $ficcao   = \App\Models\Genre::where('name', 'Ficção')->firstOrFail();
        $fantasia = \App\Models\Genre::where('name', 'Fantasia')->firstOrFail();
        $tech     = \App\Models\Genre::where('name', 'Tecnologia')->firstOrFail();
        $hist     = \App\Models\Genre::where('name', 'História')->firstOrFail();

        $books = [
            // Ficção (3)
            ['genre_id' => $ficcao->id, 'title' => '1984', 'author' => 'George Orwell', 'pages' => 328, 'status' => 'finished', 'rating' => 5],
            ['genre_id' => $ficcao->id, 'title' => 'Admirável Mundo Novo', 'author' => 'Aldous Huxley', 'pages' => 288, 'status' => 'to_read', 'rating' => null],
            ['genre_id' => $ficcao->id, 'title' => 'O Sol é Para Todos', 'author' => 'Harper Lee', 'pages' => 336, 'status' => 'reading', 'rating' => 4],

            // Fantasia (3)
            ['genre_id' => $fantasia->id, 'title' => 'O Hobbit', 'author' => 'J.R.R. Tolkien', 'pages' => 310, 'status' => 'to_read', 'rating' => null],
            ['genre_id' => $fantasia->id, 'title' => 'Harry Potter e a Pedra Filosofal', 'author' => 'J.K. Rowling', 'pages' => 223, 'status' => 'finished', 'rating' => 5],
            ['genre_id' => $fantasia->id, 'title' => 'O Nome do Vento', 'author' => 'Patrick Rothfuss', 'pages' => 662, 'status' => 'reading', 'rating' => 5],

            // Tecnologia (3)
            ['genre_id' => $tech->id, 'title' => 'Clean Code', 'author' => 'Robert C. Martin', 'pages' => 464, 'status' => 'reading', 'rating' => 5],
            ['genre_id' => $tech->id, 'title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt, David Thomas', 'pages' => 352, 'status' => 'to_read', 'rating' => null],
            ['genre_id' => $tech->id, 'title' => 'Design Patterns', 'author' => 'Erich Gamma et al.', 'pages' => 395, 'status' => 'to_read', 'rating' => null],

            // História (3)
            ['genre_id' => $hist->id, 'title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'pages' => 464, 'status' => 'finished', 'rating' => 5],
            ['genre_id' => $hist->id, 'title' => 'Guns, Germs, and Steel', 'author' => 'Jared Diamond', 'pages' => 480, 'status' => 'to_read', 'rating' => null],
            ['genre_id' => $hist->id, 'title' => 'A Segunda Guerra Mundial', 'author' => 'Antony Beevor', 'pages' => 928, 'status' => 'to_read', 'rating' => null],
        ];

        foreach ($books as $book) {
            \App\Models\Book::create($book);
        }
    }
}