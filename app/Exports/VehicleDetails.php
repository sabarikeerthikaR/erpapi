<?php

namespace App\Exports;

use App\Models\DriverProfile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class VehicleDetails implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $vehicle_details = DriverProfile::with('vehicleImages')
            ->leftJoin('users', 'users.id', '=', 'drivers_profile.user_id')
            ->select('drivers_profile.id', DB::raw('CONCAT(first_name," ",last_name) AS driver_name'), 'drivers_profile.vehicle_name', 'drivers_profile.vehicle_model', 'drivers_profile.vehicle_number', 'drivers_profile.vehicle_color', 'drivers_profile.chassis_number', 'drivers_profile.vehicle_brand')
            ->orderBy('drivers_profile.created_at', 'desc')
            ->get();

        return $vehicle_details;
    }

    public function headings(): array
    {
        return [
            "#",
            "Driver Name",
            "Vehicle Name",
            "Vehicle Model",
            "Vehicle Number",
            "Color",
            "Chassis Number",
            "Brand"
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
