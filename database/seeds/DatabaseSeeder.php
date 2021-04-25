<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CoworkersSeeder::class);
        $this->call(StationSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(AppointmentSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(ModelHasRolesSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(NotificationTemplateSeeder::class);
        $this->call(OfferSeeder::class);
        $this->call(PaymentSettingSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoleHasPermissionSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(TimeSlotSeeder::class);
        $this->call(FaqSeeder::class);
    }
}
