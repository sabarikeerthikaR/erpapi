<?php

namespace App\Exports;

use App\Models\RateReview;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class DriverActionRatings implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $driver_rating = RateReview::leftJoin('user_rides', 'user_rides.id', 'rate_reviews.ride_id')->
        leftJoin('users as u1', 'u1.id', '=', 'rate_reviews.from_user')
            ->leftJoin('users as u2', 'u2.id', 'rate_reviews.to_user')
            ->leftJoin('user_rides', 'user_rides.id', 'rate_reviews.ride_id')
            ->where('u1.type', '=', 2)
            ->select('rate_reviews.id', DB::raw('CONCAT(u2.first_name ," " , u2.last_name) AS driver_name'), DB::raw('CONCAT(u1.first_name ," " , u1.last_name) AS rider_name'), DB::raw("DATE_FORMAT(rate_reviews.created_at, '%m-%d-%Y')"), 'rate_reviews.rate', 'rate_reviews.review', 'user_rides.source as ride_source', 'user_rides.destination as ride_destination')
            ->get();

        return $driver_rating;
    }

    public function headings(): array
    {
        return [
            '#',
            'Driver Name',
            'Rider Name',
            'Date',
            'Rating',
            'Comment',
            'Source',
            'Destination',
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
