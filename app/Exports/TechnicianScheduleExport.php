<?php

namespace App\Exports;

use App\TechnicianSchedule;
use DateTime;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TechnicianScheduleExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct($date, $city, $cso, $search)
    {
    	$this->date = date($date);
        $this->city = $city;
        $this->cso = $cso;
        $this->search = $search;
    }

    public function view(): View
    {
        $this->date = strtotime($this->date);
        $technician_schedules = TechnicianSchedule::with(['cso', 'product_technician_schedule_withProduct'])
            ->whereYear('appointment', date('Y', $this->date))
            ->whereMonth('appointment', date('m', $this->date))
            ->where('active', true);

        if($this->city != null){
            $technician_schedules = $technician_schedules->where('city', $this->city);
        }
        if($this->cso != null){
            $technician_schedules = $technician_schedules->where('cso_id', $this->cso);
        }
        if($this->search != null){
            $technician_schedules = $technician_schedules->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('phone', 'like', '%'.$this->search.'%')
            ->orWhare('code', 'like', '%'.$this->search.'%');
        }
        $technician_schedules = $technician_schedules
            ->orderBy('technician_id', 'ASC')
            ->orderBy('appointment', 'ASC')->get();

        return view('admin.exports.technician_schedule1_export', [
            'technician_schedules' => $technician_schedules
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 20,
            'D' => 20,
            'E' => 30,
            'F' => 25,
            'G' => 25,
            'I' => 10,
        ];
    } 

    public function styles(Worksheet $sheet)
    {
        return [
            "A:I" => [
                'alignment' => [
                    'wrapText' => true,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                ],  
            ],
            "A" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            "A1:I4" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            
        ];
    }
}
