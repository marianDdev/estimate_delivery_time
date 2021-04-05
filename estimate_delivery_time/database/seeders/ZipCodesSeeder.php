<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class ZipCodesSeeder extends Seeder
{
    /**
     * @var Faker
     */
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $extraDays = mt_rand(3,14);
        $zipCodeId = DB::table('zip_codes')->insertGetId(
            ['zip_code' => $this->faker->unique()->randomNumber(5)],
        );

        foreach(range(1,1000) as $index) {
            $date = Carbon::create('2021', mt_rand(1,3), mt_rand(1,30));

            DB::table('delivery_dates')->insert(
                [
                    "zip_code_id" => $zipCodeId,
                    "shipment_date" => $date->format('Y-m-d H:i:s'),
                    "delivered_date" => $date->addWeekdays(mt_rand(3,14))->format('Y-m-d H:i:s'),
                ],
            );
        }
    }

}
