<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Genre::truncate();

        \App\Models\Genre::create(['name' => 'Ficção', 'description' => 'Romances e histórias fictícias']);
        \App\Models\Genre::create(['name' => 'Fantasia', 'description' => 'Mundos mágicos e criaturas míticas']);
        \App\Models\Genre::create(['name' => 'Tecnologia', 'description' => 'Programação, engenharia e computação']);
        \App\Models\Genre::create(['name' => 'História', 'description' => 'Eventos e períodos históricos']);
    }
}