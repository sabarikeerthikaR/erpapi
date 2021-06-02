<?php

namespace App\Exports;

use App\Models\Support;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class SupportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $support = (new Support())->leftJoin('users', 'users.id', '=', 'supports.user_id')
            ->select('supports.id', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'),
                'users.phone', DB::raw('DATE_FORMAT(FROM_UNIXTIME(supports.date), "%m-%d-%Y") as date'), 'supports.issue', DB::raw('(CASE WHEN support_status = 1 
THEN "Under review" WHEN support_status = 2 
THEN "Treated" 
WHEN support_status = 3 
THEN "Declined" 
WHEN support_status = 4 
THEN "Resolved"  
ELSE "N/A" END) AS 
status'))->get();

        return $support;
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Contact',
            'Date',
            'Issue',
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
