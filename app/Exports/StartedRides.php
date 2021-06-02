<?php

namespace App\Exports;

use App\Models\UserRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class StartedRides implements FromCollection,WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $rides = (new UserRide())->leftJoin('users as u1', 'u1.id', '=', 'user_rides.driver_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'user_rides.user_id')
            ->where('user_rides.status', '=', 3)
            ->select(
                'user_rides.id',
                DB::raw('CONCAT(u1.first_name, " ", u1.last_name) AS rider_name'),
                DB::raw('CONCAT(u2.first_name, " ", u2.last_name) AS driver_name'),
                'user_rides.source', 'user_rides.destination',DB::raw('(CASE WHEN user_rides.status = 1 
THEN "Posted" WHEN user_rides.status = 2 
THEN "Accepted" 
WHEN user_rides.status = 3 
THEN "Started" 
WHEN user_rides.status = 4 
THEN "Finished" 
WHEN user_rides.status = 5 
THEN "Canceled"
WHEN user_rides.status = 6 
THEN "Payment Done" 
ELSE "N/A" END) AS 
status'),
                DB::raw('DATE_FORMAT(FROM_UNIXTIME(user_rides.ride_date), "%m %d %Y") as ride_date'),
                DB::raw('IF(user_rides.ride_option = ' . 1 . ', user_rides.total_fare,user_rides.user_fare)'),
                'user_rides.drupp_earnings')
            ->orderBy('user_rides.ride_date','desc')
            ->get();


        return $rides;
    }
    public function headings(): array
    {
        return [
            '#',
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
