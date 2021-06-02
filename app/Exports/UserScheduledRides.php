<?php

namespace App\Exports;

use App\Models\UserRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class UserScheduledRides implements FromCollection,WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $scheduled_rides = UserRide::query()->leftJoin('users as u1', 'u1.id', '=', 'user_rides.user_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'user_rides.driver_id')
            ->where('ride_option', '=', 2)
            ->where('posted_by_driver', '=', 0)
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
                'user_rides.user_fare',
                'user_rides.drupp_earnings')
            ->orderBy('user_rides.ride_date', 'desc')
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
