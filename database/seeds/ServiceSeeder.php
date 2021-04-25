<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->delete();
        DB::table('services')->insert( [
            'id'=>13,
            'service_name'=>'car wash',
            'image'=>'car-service.png',
            'category_id'=>'12',
            'price'=>250,
            'coworker_id'=>'13',
            'description'=>'yaaa',
            'duration'=>30,
            'status'=>1,
            'created_at'=>'2021-01-15 22:35:12',
            'updated_at'=>'2021-01-15 22:35:12'
        ] );



        DB::table('services')->insert( [
            'id'=>18,
            'service_name'=>'Engine Wash',
            'image'=>'car spray_icon.png',
            'category_id'=>'12',
            'price'=>100,
            'coworker_id'=>'13',
            'description'=>'ya',
            'duration'=>30,
            'status'=>1,
            'created_at'=>'2021-01-30 03:35:34',
            'updated_at'=>'2021-01-30 03:35:34'
        ] );
    }
}
