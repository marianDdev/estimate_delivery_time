<?php

namespace App\Services;

use App\Repositories\DeliveryRepository;
use App\Repositories\ZipCodesRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class DeliveryService
{
    private $deliveryRepository;
    private $validator;
    private $zipCodesRepository;

    public function __construct(DeliveryRepository $deliveryRepository, ZipCodesRepository $zipCodesRepository, Validator $validator)
    {
        $this->deliveryRepository = $deliveryRepository;
        $this->validator = $validator;
        $this->zipCodesRepository = $zipCodesRepository;
    }

    public function estimateDeliveryTime(int $code): string
    {
        $zipCode = $this->zipCodesRepository->getZipCode($code);

        if($zipCode === ModelNotFoundException::class) {
            return "Zip Code not found";
        }
        $deliveryDates = $this->deliveryRepository->getDeliveryDatesByZipCode($zipCode->zip_code)->toArray();

        //array of days passed from shipment date to delivered date
        $deliveryPeriods = [];

        //get number of days passed from shipment date to delivered date for each delivery
        foreach ($deliveryDates as $date) {
            $shipmentDate = Carbon::createFromFormat('Y-m-d H:s:i', $date["shipment_date"]);
            $deliveredDate = Carbon::createFromFormat('Y-m-d H:s:i', $date["delivered_date"]);

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
