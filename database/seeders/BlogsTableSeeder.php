<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Support\Str;
use DB;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        $limit = 10;

        for($i=0; $i<50; $i++){
            DB::table('blogs')->insert([
               'title'=> $faker->name,
               'des'=> $faker->paragraph,
            //    'created_at'=> new DateTime,
            //    'updated_at'=> new DateTime,
           ]);
        }

        for($i=0; $i<50; $i++){
            DB::table('users')->insert([
                'id' => rand(100000,999999),
                'name'=> $faker->name,
                'email'=> $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'level'=> $faker->numberBetween(1,3),
                'tree'=>111111,
                'parent'=> 111111,

            ]);
        }
    }
}
