<?php

namespace App\Repositories;

use App\Models\DeliveryDate;
use Illuminate\Support\Collection;

class DeliveryRepository
{
    public function getDeliveryDatesByZipCode(string $zipCode): Collection
    {
        return DeliveryDate::select("shipment_date", "delivered_date")
                           ->join("zip_codes", "zip_codes.id", "=", "delivery_dates.zip_code_id")
                           ->where("zip_code", $zipCode)
                           ->orderBy("shipment_date")
                           ->get();
    }
}
