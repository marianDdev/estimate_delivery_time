<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeliveryRepository
{
    public function getDeliveryTimeDates(int $zipCode): Collection
    {
        return DB::table("delivery_dates")->select("delivery_dates.shipment_date", "delivery_dates.delivered_date")
                                          ->join("zip_codes", "zip_codes.id", "=", "delivery_dates.zip_code_id")
                                          ->where("zip_codes.zip_code", $zipCode)
                                          ->orderBy("delivery_dates.shipment_date")
                                          ->get();
    }
}
