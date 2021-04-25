<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert( [
            'id'=>1,
            'name'=>'admin',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:43:22',
            'updated_at'=>'2021-01-13 07:43:22'
        ] );



        DB::table('roles')->insert( [
            'id'=>2,
            'name'=>'station',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 00:07:34',
            'updated_at'=>'2021-01-15 00:07:34'
        ] );

        DB::table('roles')->insert( [
            'id'=>3,
            'name'=>'coworker',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 00:07:34',
            'updated_at'=>'2021-01-15 00:07:34'
        ] );


    }
}
