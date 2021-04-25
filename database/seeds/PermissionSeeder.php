<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        DB::table('permissions')->insert( [
            'id'=>1,
            'name'=>'role_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 05:52:46',
            'updated_at'=>'2021-01-13 05:52:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>2,
            'name'=>'role_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 06:08:21',
            'updated_at'=>'2021-01-13 06:08:21'
        ] );



        DB::table('permissions')->insert( [
            'id'=>3,
            'name'=>'category_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:46:54',
            'updated_at'=>'2021-01-13 07:46:54'
        ] );



        DB::table('permissions')->insert( [
            'id'=>4,
            'name'=>'category_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:47:46',
            'updated_at'=>'2021-01-13 07:47:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>5,
            'name'=>'category_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:47:46',
            'updated_at'=>'2021-01-13 07:47:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>6,
            'name'=>'category_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:47:46',
            'updated_at'=>'2021-01-13 07:47:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>7,
            'name'=>'offer_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:52:50',
            'updated_at'=>'2021-01-13 07:52:50'
        ] );



        DB::table('permissions')->insert( [
            'id'=>8,
            'name'=>'offer_show',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:52:50',
            'updated_at'=>'2021-01-13 07:52:50'
        ] );



        DB::table('permissions')->insert( [
            'id'=>9,
            'name'=>'offer_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:52:50',
            'updated_at'=>'2021-01-13 07:52:50'
        ] );



        DB::table('permissions')->insert( [
            'id'=>10,
            'name'=>'offer_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:52:50',
            'updated_at'=>'2021-01-13 07:52:50'
        ] );



        DB::table('permissions')->insert( [
            'id'=>11,
            'name'=>'offer_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:52:50',
            'updated_at'=>'2021-01-13 07:52:50'
        ] );



        DB::table('permissions')->insert( [
            'id'=>12,
            'name'=>'coworker_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:23',
            'updated_at'=>'2021-01-13 07:53:23'
        ] );



        DB::table('permissions')->insert( [
            'id'=>13,
            'name'=>'coworker_show',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:23',
            'updated_at'=>'2021-01-13 07:53:23'
        ] );



        DB::table('permissions')->insert( [
            'id'=>14,
            'name'=>'coworker_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:23',
            'updated_at'=>'2021-01-13 07:53:23'
        ] );



        DB::table('permissions')->insert( [
            'id'=>15,
            'name'=>'coworker_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:23',
            'updated_at'=>'2021-01-13 07:53:23'
        ] );



        DB::table('permissions')->insert( [
            'id'=>16,
            'name'=>'coworker_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:24',
            'updated_at'=>'2021-01-13 07:53:24'
        ] );



        DB::table('permissions')->insert( [
            'id'=>17,
            'name'=>'service_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:46',
            'updated_at'=>'2021-01-13 07:53:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>18,
            'name'=>'service_show',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:46',
            'updated_at'=>'2021-01-13 07:53:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>19,
            'name'=>'service_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:46',
            'updated_at'=>'2021-01-13 07:53:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>20,
            'name'=>'service_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:46',
            'updated_at'=>'2021-01-13 07:53:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>21,
            'name'=>'service_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:53:46',
            'updated_at'=>'2021-01-13 07:53:46'
        ] );



        DB::table('permissions')->insert( [
            'id'=>22,
            'name'=>'admin_appointment_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:54:22',
            'updated_at'=>'2021-01-13 07:54:22'
        ] );



        DB::table('permissions')->insert( [
            'id'=>23,
            'name'=>'admin_appointment_show',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:54:22',
            'updated_at'=>'2021-01-13 07:54:22'
        ] );



        DB::table('permissions')->insert( [
            'id'=>24,
            'name'=>'admin_appointment_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:54:22',
            'updated_at'=>'2021-01-13 07:54:22'
        ] );



        DB::table('permissions')->insert( [
            'id'=>25,
            'name'=>'admin_appointment_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:54:22',
            'updated_at'=>'2021-01-13 07:54:22'
        ] );



        DB::table('permissions')->insert( [
            'id'=>26,
            'name'=>'admin_appointment_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:54:22',
            'updated_at'=>'2021-01-13 07:54:22'
        ] );



        DB::table('permissions')->insert( [
            'id'=>27,
            'name'=>'admin_appointment_calender',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:54:55',
            'updated_at'=>'2021-01-13 07:54:55'
        ] );



        DB::table('permissions')->insert( [
            'id'=>28,
            'name'=>'notification_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:55:13',
            'updated_at'=>'2021-01-13 07:55:13'
        ] );



        DB::table('permissions')->insert( [
            'id'=>29,
            'name'=>'notification_template',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:55:33',
            'updated_at'=>'2021-01-13 07:55:33'
        ] );



        DB::table('permissions')->insert( [
            'id'=>30,
            'name'=>'user_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:56:49',
            'updated_at'=>'2021-01-13 07:56:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>31,
            'name'=>'user_show',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:56:49',
            'updated_at'=>'2021-01-13 07:56:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>32,
            'name'=>'user_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:56:49',
            'updated_at'=>'2021-01-13 07:56:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>33,
            'name'=>'user_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:56:49',
            'updated_at'=>'2021-01-13 07:56:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>34,
            'name'=>'user_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:56:49',
            'updated_at'=>'2021-01-13 07:56:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>35,
            'name'=>'language_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:15',
            'updated_at'=>'2021-01-13 07:57:15'
        ] );



        DB::table('permissions')->insert( [
            'id'=>36,
            'name'=>'language_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:15',
            'updated_at'=>'2021-01-13 07:57:15'
        ] );



        DB::table('permissions')->insert( [
            'id'=>37,
            'name'=>'language_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:15',
            'updated_at'=>'2021-01-13 07:57:15'
        ] );



        DB::table('permissions')->insert( [
            'id'=>38,
            'name'=>'language_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:15',
            'updated_at'=>'2021-01-13 07:57:15'
        ] );



        DB::table('permissions')->insert( [
            'id'=>39,
            'name'=>'faq_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:37',
            'updated_at'=>'2021-01-13 07:57:37'
        ] );



        DB::table('permissions')->insert( [
            'id'=>40,
            'name'=>'faq_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:37',
            'updated_at'=>'2021-01-13 07:57:37'
        ] );



        DB::table('permissions')->insert( [
            'id'=>41,
            'name'=>'faq_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:37',
            'updated_at'=>'2021-01-13 07:57:37'
        ] );



        DB::table('permissions')->insert( [
            'id'=>42,
            'name'=>'faq_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:57:37',
            'updated_at'=>'2021-01-13 07:57:37'
        ] );



        DB::table('permissions')->insert( [
            'id'=>43,
            'name'=>'setting_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-13 07:58:18',
            'updated_at'=>'2021-01-13 07:58:18'
        ] );



        DB::table('permissions')->insert( [
            'id'=>44,
            'name'=>'admin_dashboard',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 00:08:26',
            'updated_at'=>'2021-01-15 00:08:26'
        ] );



        DB::table('permissions')->insert( [
            'id'=>45,
            'name'=>'notification_template_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 00:36:58',
            'updated_at'=>'2021-01-15 00:36:58'
        ] );



        DB::table('permissions')->insert( [
            'id'=>46,
            'name'=>'admin_setting',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 00:36:59',
            'updated_at'=>'2021-01-15 00:36:59'
        ] );



        DB::table('permissions')->insert( [
            'id'=>47,
            'name'=>'admin_custom_notification',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 00:53:49',
            'updated_at'=>'2021-01-15 00:53:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>48,
            'name'=>'appointment_invoice',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 03:30:29',
            'updated_at'=>'2021-01-15 03:30:29'
        ] );



        DB::table('permissions')->insert( [
            'id'=>49,
            'name'=>'role_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 03:56:30',
            'updated_at'=>'2021-01-15 03:56:30'
        ] );



        DB::table('permissions')->insert( [
            'id'=>50,
            'name'=>'coworker_dashboard',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 06:24:44',
            'updated_at'=>'2021-01-15 06:24:44'
        ] );



        DB::table('permissions')->insert( [
            'id'=>51,
            'name'=>'coworker_appointment',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 06:24:44',
            'updated_at'=>'2021-01-15 06:24:44'
        ] );



        DB::table('permissions')->insert( [
            'id'=>52,
            'name'=>'coworker_review',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 06:24:44',
            'updated_at'=>'2021-01-15 06:24:44'
        ] );



        DB::table('permissions')->insert( [
            'id'=>53,
            'name'=>'coworker_profile',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 06:24:44',
            'updated_at'=>'2021-01-15 06:24:44'
        ] );



        DB::table('permissions')->insert( [
            'id'=>54,
            'name'=>'coworker_portfolio_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-15 06:24:44',
            'updated_at'=>'2021-01-15 06:24:44'
        ] );



        DB::table('permissions')->insert( [
            'id'=>55,
            'name'=>'coworker_portfolio_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>56,
            'name'=>'coworker_portfolio_update',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );



        DB::table('permissions')->insert( [
            'id'=>57,
            'name'=>'coworker_portfolio_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>58,
            'name'=>'station_access',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>59,
            'name'=>'station_show',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>60,
            'name'=>'station_profile',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>61,
            'name'=>'station_add',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>62,
            'name'=>'station_edit',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>63,
            'name'=>'station_delete',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>64,
            'name'=>'station_dashboard',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>65,
            'name'=>'station_appointment',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );

        DB::table('permissions')->insert( [
            'id'=>66,
            'name'=>'station_review',
            'guard_name'=>'web',
            'created_at'=>'2021-01-16 01:53:49',
            'updated_at'=>'2021-01-16 01:53:49'
        ] );
    }
}
