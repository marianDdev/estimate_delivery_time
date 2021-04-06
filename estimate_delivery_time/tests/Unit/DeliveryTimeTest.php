<?php

namespace Tests\Unit;

use App\DeliveryRepository;
use App\Services\DeliveryService;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;

class DeliveryTimeTest extends TestCase
{
    public function test_can_estimate_delivery_time(): void
    {
        $deliveryRepository = Mockery::mock(DeliveryRepository::class);
        $deliveryRepository->shouldReceive('getDeliveryTimeDates')->with(96708)
                           ->andReturn(collect([
                                                   [
                                                       "shipment_date" => "2021-04-08 22:44:44",
                                                       "delivered_date" => "2021-04-14 22:44:44",
                                                   ],
                                                   [
                                                       "shipment_date" => "2021-03-08 22:44:44",
                                                       "delivered_date" => "2021-03-14 22:44:44",
                                                   ]
                                               ]));



        $input = ["zip_code" => 96708];
        $expected = Carbon::now()->addDays(8)->toDateTimeString();

        /** @var DeliveryService $deliveryService */
        $deliveryService = $this->app->get(DeliveryService::class);
        $actual = $deliveryService->estimateDeliveryTime($input);

        $this->assertEquals($expected, $actual);
    }
}
