<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class DriversExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $drivers = User::where('type', '=', 2)->select('users.id', DB::raw('CONCAT(first_name," ",last_name) as Name'), DB::raw('CONCAT("+",country_code,"-",phone) as Phone'), 'email', 'city', DB::raw('(CASE WHEN users.status = 1 
THEN "Active" WHEN users.status = 0 
THEN "Inactive" 
ELSE "N/A" END) AS 
status'))
            ->orderBy('users.created_at', 'desc')->get();
        return $drivers;
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Phone',
            'Email',
            'City',
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
