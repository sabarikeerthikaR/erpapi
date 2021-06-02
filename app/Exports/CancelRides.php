<?php

namespace App\Exports;

use App\Models\UserRide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class CancelRides implements FromCollection,WithHeadings, ShouldAutoSize, WithEvents
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
       $type = $request->input('type');
        $search = $request->input('search');
         $user = DB::table("user_rides as r")
            ->join('users as u1', 'r.user_id', '=', 'u1.id')->where("r.status",5)->groupBy("r.user_id");
       
        if($type=="2")
        {
            $user->
            where(function($query){
                $query
                ->where("u1.type",'=','2')
                ->where("r.posted_by_driver",'=',1);
           });
           
        }
        else{
            $user->
            where(function($query){
                $query
                ->where("u1.type",'=','1')
                ->where("r.posted_by_driver",'=',0);
           });
            
        } 

        if ($search) {

            $user->
            where(function ($query) use ($search) {
                $query->where(DB::raw("CONCAT(u1.first_name, ' ', u1.last_name)"), 'LIKE', '%' . $search . '%')
                   
                    ->orWhere('r.source', 'LIKE', '%' . $search . '%')
                    ->orWhere('r.destination', 'LIKE', '%' . $search . '%');
            });
            

        }
        $startdate=strtotime($request->startD);
        $endDate=strtotime($request->endD);
        if(checkdate(date("m",$startdate), date("d",$startdate), date("Y",$startdate))&&is_numeric($startdate))
        {
            $user->
            where(function($query)use($startdate,$endDate)
            {
               $query->where("ride_date",">=",$startdate)
               ->where("ride_date","<=",$endDate);
            });
        }
        $user->
        select(DB::raw('CONCAT(u1.first_name, " ", u1.last_name) AS Driver_name'),'phone as uphone','email as uemail'
           ,DB::raw('count(r.user_id) as totalCount'),'r.cancel_reason as cancelText','u1.status as ustatus')
            ->orderBy('totalCount', 'desc');
            return $user->get();
    }
    
    public function headings(): array
    {
        return [
            
            'Name',
            'Phone',
            'Email',
            'Count',
            'Reason',
            'Status',
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
