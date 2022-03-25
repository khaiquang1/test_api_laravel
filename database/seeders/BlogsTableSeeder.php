<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator;
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
    }
}
