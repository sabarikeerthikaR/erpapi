<?php

namespace App\Exports;

use App\Models\DriverRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class DriverScheduledRides implements FromCollection,WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $scheduled_rides = DriverRide::query()->leftJoin('request_rides', 'request_rides.driver_ride_id', '=', 'driver_rides.id')
            ->leftJoin('users as u1', 'u1.id', '=', 'driver_rides.driver_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'request_rides.user_id')
            ->select('driver_rides.id', DB::raw('CONCAT(u1.first_name, " ", u1.last_name) AS driver_name'),
                DB::raw('CONCAT(u2.first_name, " ", u2.last_name) AS rider_name'), 'driver_rides.source', 'driver_rides.destination', DB::raw('(CASE WHEN driver_rides.status = 1 AND request_rides.status = 2
 THEN "Accepted"
WHEN driver_rides.status = 1 
THEN "posted"
WHEN driver_rides.status = 2 
THEN "Started" 
WHEN driver_rides.status = 3 
THEN "Finished" 
WHEN driver_rides.status = 4 
THEN "Canceled"
WHEN driver_rides.status = 5 
THEN "Payment Done" 
ELSE "N/A" END) AS 
status'),
                DB::raw('DATE_FORMAT(FROM_UNIXTIME(driver_rides.ride_date), "%m %d %Y") as ride_date'),
                'driver_rides.driver_fare',
                'driver_rides.drupp_earnings')
            ->orderBy('driver_rides.ride_date', 'desc')
            ->get();
        return $scheduled_rides;
    }

    public function headings(): array
    {
        return [
            '#',
            'Driver',
            'Rider',
            'Source',
            'Destination',
            'Status',
            'Date',
            'Fare',
            'Drupp commission'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
