<?php

namespace App\Exports;

use App\Models\DriverRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class SingleDriverAllPostedRides implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $driver_id;

    function __construct($driver_id) {
        $this->driver_id = $driver_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $posted_rides = DriverRide::with(['rateReview' => function ($query) {
            $query->where('from_user', '=', $this->driver_id)->select('from_user', 'to_user', 'rate', 'review', 'ride_id');
        }])->leftJoin('request_rides', function ($query) {
            $query->on('request_rides.driver_ride_id', '=', 'driver_rides.id')
                ->leftJoin('users as user', 'user.id', '=', 'request_rides.user_id');
        })->leftJoin('users as driver', 'driver.id', '=', 'driver_rides.driver_id')
            ->where('driver_rides.driver_id', '=', $this->driver_id)
            ->orderBy('driver_rides.created_at', 'desc')
            ->select('driver_rides.id','driver_rides.source','driver_rides.destination', 'driver_rides.ride_date', DB::raw('IF(driver_rides.ride_type = ' . 1 . ', "Single Ride","Pool ride")'), DB::raw('(CASE WHEN driver_rides.status = 1 
THEN "Posted" 
WHEN driver_rides.status = 2
THEN "Started" 
WHEN driver_rides.status = 3 
THEN "Finished" 
WHEN driver_rides.status = 4 
THEN "Canceled"
WHEN driver_rides.status = 5 
THEN "Payment Done" 
ELSE "N/A" END) AS 
status'),'driver_rides.driver_fare')->get();

        return $posted_rides;
    }
    public function headings(): array
    {
        return [
            'Ride_id',
            'Source',
            'Destination',
            'Date',
            'Ride type',
            'Status',
            'Fare'
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
