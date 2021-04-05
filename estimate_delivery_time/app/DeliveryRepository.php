<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeliveryRepository
{
    public function getDeliveryTimeDates(int $zipCode): Collection
    {
        return DB::table("delivery_dates")->select("shipment_date", "delivered_date")
                                          ->join("zip_codes", "zip_codes.id", "=", "delivery_dates.zip_code_id")
                                          ->where("zip_code", $zipCode)
                                          ->orderBy("shipment_date")
                                          ->get();
    }
}
