<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert( [
            'id'=>1,
            'name'=>'admin',
            'image'=>'162-1623921_stewardess-510x510-user-profile-icon-png.png',
            'phone'=>'8320122957',
            'phone_code'=>NULL,
            'is_verified'=>1,
            'otp'=>4088,
            'device_token'=>'',
            'email'=>'adminMagasil@gmail.com',
            'status'=>1,
            'provider'=>NULL,
            'provider_token'=>NULL,
            'email_verified_at'=> Null,
            'password'=>'$2y$10$JSvWmjHkfWgMHfYXw3sLbeyPpkTvNHXKkWVoPtLa/B7FTqP450oea',
            'remember_token'=>NULL,
            'created_at'=>'2020-09-17 07:14:41',
            'updated_at'=>'2020-10-15 07:49:52'
        ] );



        DB::table('users')->insert( [
            'id'=>2,
            'name'=>'hasan',
            'image'=>'noimage.jpg',
            'phone'=>'1234567890',
            'phone_code'=>NULL,
            'is_verified'=>1,
            'otp'=>6777,
            'device_token'=>'7e9d810e-b053-4538-a06d-7fed92b0a8ac',
            'email'=>'hasan@gmail.com',
            'status'=>1,
            'provider'=>NULL,
            'provider_token'=>NULL,
            'email_verified_at'=>NULL,
            'password'=>'$2y$10$JSvWmjHkfWgMHfYXw3sLbeyPpkTvNHXKkWVoPtLa/B7FTqP450oea',
            'remember_token'=>NULL,
            'created_at'=>'2021-01-15 06:03:54',
            'updated_at'=>'2021-01-21 00:11:23'
        ] );



        DB::table('users')->insert( [
            'id'=>3,
            'name'=>'Test',
            'image'=>'noimage.jpg',
            'phone'=>'4567891230',
            'phone_code'=>NULL,
            'is_verified'=>1,
            'otp'=>NULL,
            'device_token'=>NULL,
            'email'=>'test@gmail.com',
            'status'=>1,
            'provider'=>NULL,
            'provider_token'=>NULL,
            'email_verified_at'=>NULL,
            'password'=>'$2y$10$JSvWmjHkfWgMHfYXw3sLbeyPpkTvNHXKkWVoPtLa/B7FTqP450oea',
            'remember_token'=>NULL,
            'created_at'=>'2021-01-15 22:30:12',
            'updated_at'=>'2021-01-15 22:30:12'
        ] );



        DB::table('users')->insert( [
            'id'=>4,
            'name'=>'omar',
            'image'=>'noimage.jpg',
            'phone'=>'4567891230',
            'phone_code'=>NULL,
            'is_verified'=>1,
            'otp'=>NULL,
            'device_token'=>NULL,
            'email'=>'omar@gmail.com',
            'status'=>1,
            'provider'=>NULL,
            'provider_token'=>NULL,
            'email_verified_at'=>NULL,
            'password'=>'$2y$10$OtgC5EABmpVQV5Rjm45C.uVY.I/S/Esont13I5jpvjly6b28Lp5HC',
            'remember_token'=>NULL,
            'created_at'=>'2021-01-15 22:34:38',
            'updated_at'=>'2021-01-15 22:34:38'
        ] );


    }
}
