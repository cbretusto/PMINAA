<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

Use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\sheets\ExportFgsSummarySheet;
use App\Exports\sheets\ExportRawMaterialSummarySheet;

class ExportData implements WithMultipleSheets
{
    use Exportable;
    protected $data;

    function __construct(
        $data,
        $category,
        $user
    ){
        $this->data = $data;
        $this->category = $category;
        $this->user = $user;
    }

    public function sheets(): array{
        $sheets = [];
        if ($this->category == 1) {
            $sheets[] = new ExportFgsSummarySheet($this->data, $this->user);
        }else{
            $sheets[] = new ExportRawMaterialSummarySheet($this->data, $this->user);
        }

        return $sheets;
    }
}
