<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 0; $i < 20; $i++) {
            DB::table('videos')->insert([
                'user_id' => 1,
                'name' => 'Loki ep.' . ($i+1),
                'description' => 'Loki ep.' . ($i+1),
                'thumbnail' => 'https://res.cloudinary.com/dhmfdihb0/image/upload/v1701093597/wy7rlhjs7alsb1r1xgrw.jpg',
                'src' => 'https://res.cloudinary.com/dhmfdihb0/video/upload/v1701093601/iwvweesvrpyisresfber.mp4',
                'status' => 1,
                'view' => rand(200, 300),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
