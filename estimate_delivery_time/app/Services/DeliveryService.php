<?php

namespace App\Services;

use App\DeliveryRepository;
use Carbon\Carbon;
use Illuminate\Validation\Factory as Validator;

class DeliveryService
{
    private $deliveryRepository;
    private $validator;

    public function __construct(DeliveryRepository $deliveryRepository, Validator $validator)
    {
        $this->deliveryRepository = $deliveryRepository;
        $this->validator = $validator;
    }

    public function estimateDeliveryTime(array $input): string
    {

            $validated = $this->validator->make(
                $input,
                [
                    "zip_code" => ["required", "int", "min:5"],
                ],
                [
                    "zip_code.required" => "required",
                    "zip_code.int" => "integer",
                    "zip_code.min" => "min::min"
                ]
            )->validate();

       ["zip_code" => $zipCode] = $validated;

        //all the shipment and delivered dates related to requested zip code
        $deliveryDates = $this->deliveryRepository->getDeliveryTimeDates($zipCode);

        //array of days passed from shipment date to delivered date
        $deliveryPeriods = [];

        //get number of days passed from shipment date to delivered date for each delivery
        foreach ($deliveryDates as $date) {
            $shipmentDate = Carbon::createFromFormat('Y-m-d H:s:i', $date->shipment_date);
            $deliveredDate = Carbon::createFromFormat('Y-m-d H:s:i', $date->delivered_date);

            $deliveryPeriods[] = $deliveredDate->diffInWeekdays($shipmentDate);
        }

        //count the number of occurences for each number of days
        //passed from shipment date to delivered date
        //and get the number of days with highest frequency
        $deliveryPeriodsCount = array_count_values($deliveryPeriods);
        arsort($deliveryPeriodsCount);

       $mostFrequentDeliveryPeriod = array_key_first($deliveryPeriodsCount);

       //estimate delivery date by adding the most frequent delivery period of days to current date
        return Carbon::now()->addDays($mostFrequentDeliveryPeriod)->toDateTimeString();
    }

}
