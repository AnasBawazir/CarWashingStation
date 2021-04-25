<?php

use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('time_slots')->delete();
        DB::table('time_slots')->insert([
            'id'=>1,
            'time'=>'12:00 AM',
            'status'=>1

        ]);
        DB::table('time_slots')->insert([
            'id'=>2,
            'time'=>'12:30 AM',
            'status'=>1

        ]);
        DB::table('time_slots')->insert([
            'id'=>3,
            'time'=>'01:00 AM',
            'status'=>1

        ]);
        DB::table('time_slots')->insert([
            'id'=>4,
            'time'=>'01:30 AM',
            'status'=>1

        ]);
        DB::table('time_slots')->insert([

            'time'=>'02:00 AM',
            'status'=>1

        ]);

    }
}
