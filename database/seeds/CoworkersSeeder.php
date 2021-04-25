<?php

use Illuminate\Database\Seeder;

class CoworkersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(\App\Models\Coworkers::class,4)->create();
        DB::table('coworkers')->delete();
        DB::table('coworkers')->insert([
            'id'=>1,
            'name'=>'hasan',
            'user_id'=>2,
            'image'=>'noimage.jpg',
            'phone'=>'1234567890',
            'start_time'=>'12:00 am',
            'end_time'=>'05:00 pm',
            'experience'=>2,
            'email'=>'hasan@gmail.com',
            'status'=>1,
            'description'=>NULL,
            'password'=>'$2y$10$JSvWmjHkfWgMHfYXw3sLbeyPpkTvNHXKkWVoPtLa/B7FTqP450oea',
            'remember_token'=>NULL,
        ]);
    }
}
