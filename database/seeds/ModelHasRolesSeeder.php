<?php

use Illuminate\Database\Seeder;

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_roles')->delete();
        DB::table('model_has_roles')->insert( [
            'role_id'=>1,
            'model_type'=>'App\\User',
            'model_id'=>1
        ] );



        DB::table('model_has_roles')->insert( [
            'role_id'=>2,
            'model_type'=>'App\\User',
            'model_id'=>4
        ] );



        DB::table('model_has_roles')->insert( [
            'role_id'=>3,
            'model_type'=>'App\\User',
            'model_id'=>2
        ] );



        DB::table('model_has_roles')->insert( [
            'role_id'=>3,
            'model_type'=>'App\\User',
            'model_id'=>3
        ] );

    }
}
