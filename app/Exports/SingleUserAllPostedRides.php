<?php

namespace App\Exports;

use App\Models\UserRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class SingleUserAllPostedRides implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $user_id;

    function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */


    public function collection()
    {
        $posted_rides = UserRide::with(['rateReview' => function ($query) {
            $query->where('from_user', '=', $this->user_id)->select('from_user', 'to_user', 'rate', 'review', 'ride_id');
        }])
            ->leftJoin('users as user', 'user.id', '=', 'user_rides.user_id')
            ->leftJoin('users as driver', 'driver.id', '=', 'user_rides.driver_id')
            ->where('user_rides.user_id', '=', $this->user_id)
            ->where('user_rides.posted_by_driver', '=', 0)
            ->orderBy('user_rides.created_at', 'desc')
            ->select('user_rides.id', 'user_rides.source', 'user_rides.destination', 'user_rides.ride_date', DB::raw('IF(user_rides.ride_type = ' . 1 . ', "Single Ride","Pool ride")'), DB::raw('(CASE WHEN user_rides.status = 1 
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
status'), DB::raw('IF(user_rides.ride_option = ' . 1 . ', user_rides.total_fare,user_rides.user_fare)'))
            ->get();

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
