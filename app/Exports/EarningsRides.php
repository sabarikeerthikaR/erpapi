<?php

namespace App\Exports;

use App\Models\UserRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class EarningsRides implements FromCollection,WithHeadings, ShouldAutoSize, WithEvents
{
    protected $request;

    function __construct($request) {
       $this->request=$request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       $request=$this->request;
       $allFare=DB::table("user_rides as r")->where('drupp_earnings','<>','NULL');
        if($request->ride_type)
        {
            $allFare->where(function($query)use($request){
                $query->where("ride_type",$request->ride_type);
            });

        }
        if($request->user_type)
        {
            $allFare->where(function($query)use($request){
                $query->where("posted_by_driver",$request->user_type);
            });
            if($request->user_type==0)
                $allFare->join("users as u1","u1.id","=","r.user_id")->groupBy("r.user_id");
            else
                $allFare->join("users as u1","u1.id","=","r.driver_id")->groupBy("r.driver_id");
        }
        else
        {
            $allFare->join("users as u1","u1.id","=","r.user_id");
        }
        if($request->payment_type)
        {
            $allFare->where(function($query)use($request){
                $query->where("payment_option",$request->payment_type);
            });
        }
        if($request->startDate && $request->endDate)
        {
            $startDate=strtotime($request->startDate);
            $endDate=strtotime($request->endDate);
            $allFare->where(function($query)use($startDate,$endDate){
                $query->where("ride_date",">=",$startDate)->where("ride_date","<=",$endDate);
            });
        }

        $allFare=$allFare
        ->select('u1.id as uid',
            DB::raw('CONCAT(u1.first_name, " ", u1.last_name) AS name'),
            'u1.phone as phone','u1.email as email',
            DB::raw('sum(r.total_fare) as expenses'),
            DB::raw('sum(r.drupp_earnings) as income'),
            'u1.status as ustatus',
            'u1.profile_picture as image',
            'u1.country_code as country_code',
            'r.posted_by_driver as driverType',
            'r.ride_type as ride_type',
            'r.payment_option as payment'
            )
        ->get();
        return $allFare;
    }
    public function headings(): array
    {
        return [
            'Registration ID',
            'Name',
            'Phone',
            'Email',
            'Status',
            'Driver Type',
            'Trip Type',
            'Payment Type',
            'Income/Expenses',
        ];
    }
    public function map($invoice): array
    {
        $status="Active";
        if($invoice->ustatus=="0") $status="Blocked";
        $driverType="Driver";
        if($invoice->driverType=="0") $driverType="Rider";
        $tripType="Regular";
        if($invoice->ride_type=="2") $tripType="Luxury";
        if($invoice->ride_type=="3") $tripType="Kekes";
        $paymentTypeArr=array("1"=>"Credit card","2"=>"Debit card","3"=>"Wallet","4"=>"NetBanking","5"=>"Cash");
        $paymentType=$paymentTypeArr[$invoice->payment];
        if($invoice->driverType==0) $income=number_format(($invoice->expenses-$invoice->income)-($invoice->expenses*7.5/100),2);
        else $income=number_format($item->income,2);
        return [
            $invoice->uid,
            $invoice->name,
            "+",$invoice->country_code." ".$invoice->phone,
            $invoice->email,
            $status,
            $driverType,
            $tripType,
            $paymentType,
            $income
            
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
