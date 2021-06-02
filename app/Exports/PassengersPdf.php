<?php

namespace App\Exports;

use App\Models\BusRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class PassengersPdf implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $ride_id;

    function __construct($ride_id)
    {
        $this->ride_id = $ride_id;
    }

    public function collection()
    {
        $passengers = BusRide::leftJoin('bus_ride_details', 'bus_ride_details.bus_ride_id', '=', 'bus_rides.id')->leftJoin('allocated_bus_seats', 'allocated_bus_seats.bus_ride_detail_id', 'bus_ride_details.id')
            ->where('bus_rides.id', '=', $this->ride_id)
            ->select('user_id','user_name',DB::raw('(CASE WHEN allocated_bus_seats.status = 1 
THEN "Booked" WHEN allocated_bus_seats.status = 2 
THEN "On Board" 
WHEN allocated_bus_seats.status = 3 
THEN "Not Boarded" 
WHEN allocated_bus_seats.status = 4 
THEN "Canceled"  
ELSE "N/A" END) AS 
status'))
            ->get();

        return $passengers;
    }

    public function headings(): array
    {
        return [
            'User Id',
            'Passenger',
            'Status'
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
