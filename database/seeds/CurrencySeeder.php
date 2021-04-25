<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->delete();
        DB::table('currencies')->insert( [
            'id'=>1,
            'country'=>'Saudi Arabia',
            'currency'=>'Riyals',
            'code'=>'SAR',
            'symbol'=>'﷼'
        ] );



        DB::table('currencies')->insert( [
            'id'=>2,
            'country'=>'United States of America',
            'currency'=>'Dollars',
            'code'=>'USD',
            'symbol'=>'$'
        ] );
    }
}
