<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->delete();
        DB::table('languages')->insert( [
            'id'=>1,
            'name'=>'en',
            'file'=>'en.json',
            'image'=>'usa.png',
            'direction'=>'ltr',
            'status'=>1,
            'created_at'=>'2020-10-13 06:51:22',
            'updated_at'=>'2020-10-15 07:58:41'
        ] );



        DB::table('languages')->insert( [
            'id'=>2,
            'name'=>'ar',
            'file'=>'ar.json',
            'image'=>'iran.png',
            'direction'=>'rtl',
            'status'=>1,
            'created_at'=>'2020-10-13 07:33:05',
            'updated_at'=>'2020-10-15 03:09:40'
        ] );
    }
}
