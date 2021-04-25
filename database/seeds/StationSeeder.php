<?php

use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stations')->delete();
        DB::table('stations')->insert([
            'id'=>1,
            'name'=>'omar',
            'user_id'=>4,
            'image'=>'noimage.jpg',
            'phone'=>'4567891230',
//            'start_time'=>'12:00 am',
//            'end_time'=>'05:00 pm',
            'email'=>'omar@gmail.com',
            'status'=>1,
            'description'=>NULL,
            'Location'=>NULL,
            'password'=>'$2y$10$JSvWmjHkfWgMHfYXw3sLbeyPpkTvNHXKkWVoPtLa/B7FTqP450oea',
            'remember_token'=>NULL,
        ]);
    }
}
