<?php

namespace Tests\Unit;

use App\DeliveryRepository;
use App\Services\DeliveryService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\TestCase;

class DeliveryTimeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $deliveryRepository = Mockery::mock(DeliveryRepository::class);
        $deliveryRepository->shouldReceive('getDeliveryTimeDates')->with("96708")
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

        $deliveryRepository->shouldReceive('getDeliveryTimeDates')->with("10000")
                           ->andThrow(ModelNotFoundException::class);

    }

    public function test_can_estimate_delivery_time(): void
    {

        $expected = Carbon::now()->addDays(8)->toDateTimeString();

        /** @var DeliveryService $deliveryService */
        $deliveryService = $this->app->get(DeliveryService::class);
        $actual = $deliveryService->estimateDeliveryTime("96708");

        $this->assertEquals($expected, $actual);
    }

    public function test_can_not_find_zip_code() {

        //$this->expectException(ModelNotFoundException::class);

        $expected = "Zip Code not found";

        /** @var DeliveryService $deliveryService */
        $deliveryService = $this->app->get(DeliveryService::class);
        $actual = $deliveryService->estimateDeliveryTime("10000");

        $this->assertEquals($expected, $actual);
    }
}
