<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        DB::table('categories')->insert([
            'id'=>11,
            'category_name'=>'Platinum wash',
            'image'=>'5-Benefits-Of-Using-Car-Polish.png',
            'status'=>1,
            'created_at'=>'2021-01-15 22:32:47',
            'updated_at'=>'2021-01-15 22:32:47',
        ]);
        DB::table('categories')->insert([
            'id'=>12,
            'category_name'=>'golden wash',
            'image'=>'blackout.png',
            'status'=>1,
            'created_at'=>'2021-01-15 22:33:03',
            'updated_at'=>'2021-01-15 22:33:03'
        ]);
    }
}
