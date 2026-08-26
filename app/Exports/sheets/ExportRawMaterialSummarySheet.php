<?php

namespace App\Exports\sheets;

use Illuminate\Contracts\View\View;

Use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\Style\Alignment;



class ExportRawMaterialSummarySheet implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    use Exportable;
    protected $data;

    function __construct(
        $data
    ){
        $this->data = $data;
    }

    public function view(): View {
        return view('exports.export_report', [
            'data' => $this->data
        ]);
    }

    public function title(): string{
        return 'Raw Material Summary';
    }

    //for designs
    public function registerEvents(): array{
        $data = $this->data;

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $text_center = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ];

        $text_align_left = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );
        $font_8_arial = array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  8,
            ]
        );

        $font_9_arial_bold = array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  9,
                'bold'      =>  true,
            ]
        );

        $font_10_arial_bold = array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  10,
                'bold'      =>  true,
            ]
        );

        return[AfterSheet::class => function(AfterSheet $event) use(
            $data,
            $border, 
            $text_center, 
            $text_align_left, 
            $font_8_arial, 
            $font_9_arial_bold, 
            $font_10_arial_bold
        ){
            //==================== Excel Format =========================
            $excel = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
            $event->sheet
            ->getDelegate()
            // ->getStyle('A1')
            ->getStyle('A3:P3')
            ->getFill()
            ->setFillType($excel)
            ->getStartColor()
            ->setARGB('9fc5e8');

            // $event->sheet->freezePane('');
            $event->sheet->getDelegate()->getStyle('A3:P3')->applyFromArray($border);
            $event->sheet->getColumnDimension('A')->setWidth(20);
            $event->sheet->getColumnDimension('B')->setWidth(20);
            $event->sheet->getColumnDimension('C')->setWidth(20);
            $event->sheet->getColumnDimension('D')->setWidth(15);
            $event->sheet->getColumnDimension('E')->setWidth(15);
            $event->sheet->getColumnDimension('F')->setWidth(20);
            $event->sheet->getColumnDimension('G')->setWidth(15);
            $event->sheet->getColumnDimension('H')->setWidth(15);
            $event->sheet->getColumnDimension('I')->setWidth(20);
            $event->sheet->getColumnDimension('J')->setWidth(15);
            $event->sheet->getColumnDimension('K')->setWidth(15);
            $event->sheet->getColumnDimension('L')->setWidth(20);
            $event->sheet->getColumnDimension('M')->setWidth(20);
            $event->sheet->getColumnDimension('N')->setWidth(20);
            $event->sheet->getColumnDimension('O')->setWidth(20);
            $event->sheet->getColumnDimension('P')->setWidth(20);

            $event->sheet
                ->getDelegate()
                ->mergeCells('A1:P2');

            $event->sheet
                ->getDelegate()
                ->getStyle('A1')
                ->applyFromArray($text_center)
                ->applyFromArray($font_10_arial_bold)
                ->getAlignment()
                ->setWrapText(true);

            $event->sheet
                ->getDelegate()
                ->getStyle('A3:P3')
                ->applyFromArray($text_center)
                ->applyFromArray($font_9_arial_bold)
                ->getAlignment()
                ->setWrapText(true);
            
            $event->sheet->setCellValue('A1',"RAW MATERIAL - REVISED SELLING PRICE");
            
            $event->sheet->setCellValue('A3',"  Invoice No.");
            $event->sheet->setCellValue('B3',"  Packing List \nControl No.");
            $event->sheet->setCellValue('C3',"  Invoice \nControl No.");
            $event->sheet->setCellValue('D3',"  Revised \nDate");
            $event->sheet->setCellValue('E3',"  Revision \nNo.");
            $event->sheet->setCellValue('F3',"  Material \nCode");
            $event->sheet->setCellValue('G3',"  Material \nName");
            $event->sheet->setCellValue('H3',"  New \nPrice");
            $event->sheet->setCellValue('I3',"  Old \nPrice");
            $event->sheet->setCellValue('J3',"  Quantity");
            $event->sheet->setCellValue('K3',"  Remark");
            $event->sheet->setCellValue('L3',"  Requested By");
            $event->sheet->setCellValue('M3',"  Checked By");
            $event->sheet->setCellValue('N3',"  Noted By");
            $event->sheet->setCellValue('O3',"  Approved By");
            $event->sheet->setCellValue('P3',"  Conformed By");

            $raw_material_start_row = 4;
            $raw_material_invoice_data = count($data); 
            for ($rawMaterialInvoiceRecord = 0; $rawMaterialInvoiceRecord < $raw_material_invoice_data; $rawMaterialInvoiceRecord++) {
                $raw_material_merge_data = $data[$rawMaterialInvoiceRecord];
                $raw_material_invoice_info = $raw_material_merge_data->invoicing_info;
            
                if ($raw_material_invoice_info != null) {
                    $raw_material_invoice_to_merge = $raw_material_invoice_info->invoice_no . '|' . $raw_material_invoice_info->transaction_no;
                    $raw_material_invoice_count = 1;
            
                    // Count how many rows to merge for invoice
                    for ($rawMaterialInvoiceMergeLoop = $rawMaterialInvoiceRecord + 1; $rawMaterialInvoiceMergeLoop < $raw_material_invoice_data; $rawMaterialInvoiceMergeLoop++) {
                        $raw_material_merge_data = $data[$rawMaterialInvoiceMergeLoop]->invoicing_info;
                        if ($raw_material_merge_data != null) {
                            $raw_material_next_row = $raw_material_merge_data->invoice_no . '|' . $raw_material_merge_data->transaction_no;
                            if ($raw_material_next_row === $raw_material_invoice_to_merge) {
                                $raw_material_invoice_count++;
                            } else {
                                break;
                            }
                        }
                    }
            
                    $raw_material_invoice_end_row = $raw_material_start_row + $raw_material_invoice_count - 1;
            
                    // Merge columns A-C for invoice
                    if ($raw_material_invoice_count > 1) {
                        $event->sheet->mergeCells("A{$raw_material_start_row}:A{$raw_material_invoice_end_row}");
                        $event->sheet->mergeCells("B{$raw_material_start_row}:B{$raw_material_invoice_end_row}");
                        // $event->sheet->mergeCells("C{$raw_material_start_row}:C{$raw_material_invoice_end_row}");
                    }
            
                    $event->sheet->setCellValue("A{$raw_material_start_row}", $raw_material_invoice_info->invoice_no);
                    $event->sheet->setCellValue("B{$raw_material_start_row}", $raw_material_invoice_info->packing_list_ctrl);
                    // $event->sheet->setCellValue("C{$raw_material_start_row}", $raw_material_invoice_info->packing_list_ctrl);
            
                    // Handle D-E merging by revision_no within invoice group
                    $raw_material_revision_row = 0;
                    while ($raw_material_revision_row < $raw_material_invoice_count) {
                        $raw_material_revision_start_row = $raw_material_start_row + $raw_material_revision_row;
                        $raw_material_current_revision = $data[$rawMaterialInvoiceRecord + $raw_material_revision_row]->revision_no;
                        $raw_material_revision_count = 1;
            
                        // Count consecutive rows with same revision_no
                        while (($raw_material_revision_row + $raw_material_revision_count) < $raw_material_invoice_count &&  $data[$rawMaterialInvoiceRecord + $raw_material_revision_row + $raw_material_revision_count]->revision_no === $raw_material_current_revision){
                                $raw_material_revision_count++;
                        }
            
                        $raw_material_revision_end_row = $raw_material_revision_start_row + $raw_material_revision_count - 1;
            
                        if ($raw_material_revision_count > 1) {
                            $event->sheet->mergeCells("C{$raw_material_revision_start_row}:C{$raw_material_revision_end_row}");
                            $event->sheet->mergeCells("D{$raw_material_revision_start_row}:D{$raw_material_revision_end_row}");
                            $event->sheet->mergeCells("E{$raw_material_revision_start_row}:E{$raw_material_revision_end_row}");
                        
                            $event->sheet->mergeCells("L{$raw_material_revision_start_row}:L{$raw_material_revision_end_row}");
                            $event->sheet->mergeCells("M{$raw_material_revision_start_row}:M{$raw_material_revision_end_row}");
                            $event->sheet->mergeCells("N{$raw_material_revision_start_row}:N{$raw_material_revision_end_row}");
                            $event->sheet->mergeCells("O{$raw_material_revision_start_row}:O{$raw_material_revision_end_row}");
                            $event->sheet->mergeCells("P{$raw_material_revision_start_row}:P{$raw_material_revision_end_row}");
                        }
            
                        $event->sheet->setCellValue("C{$raw_material_revision_start_row}", $data[$rawMaterialInvoiceRecord + $raw_material_revision_row]->control_no);
                        $event->sheet->setCellValue("D{$raw_material_revision_start_row}", $raw_material_current_revision);
                        $event->sheet->setCellValue("E{$raw_material_revision_start_row}", $data[$rawMaterialInvoiceRecord + $raw_material_revision_row]->revised_date);
            
                        $approver = $data[$rawMaterialInvoiceRecord + $raw_material_revision_row]->invoicing_info->invoice_approver_details;
                        for ($i=0; $i < count($approver); $i++) { 
                            if($raw_material_current_revision == $approver[$i]->revision_no){
                                $event->sheet->setCellValue("L{$raw_material_revision_start_row}", $approver[$i]->approver_requested_by_info->name);
                                $event->sheet->setCellValue("M{$raw_material_revision_start_row}", $approver[$i]->approver_checked_by_info->name);
                                $event->sheet->setCellValue("N{$raw_material_revision_start_row}", $approver[$i]->approver_noted_by_info->name);
                                $event->sheet->setCellValue("O{$raw_material_revision_start_row}", $approver[$i]->approver_approved_by_info->name);
                                $event->sheet->setCellValue("P{$raw_material_revision_start_row}", $approver[$i]->approver_conformed_by_info->name);
                            }
                        }

                        $raw_material_revision_row += $raw_material_revision_count;
                    }
            
                    for ($invoiceGroup = 0; $invoiceGroup < $raw_material_invoice_count; $invoiceGroup++) {
                        $raw_material_row = $raw_material_start_row + $invoiceGroup;
                        $raw_material_row_data = $data[$rawMaterialInvoiceRecord + $invoiceGroup];

                        $event->sheet->setCellValue("F{$raw_material_row}", $raw_material_row_data->material_code);
                        $event->sheet->setCellValue("G{$raw_material_row}", $raw_material_row_data->material_name);
                        $event->sheet->setCellValue("H{$raw_material_row}", $raw_material_row_data->price_from);
                        $event->sheet->setCellValue("I{$raw_material_row}", $raw_material_row_data->price_to);
                        $event->sheet->setCellValue("J{$raw_material_row}", $raw_material_row_data->qty);
                        $event->sheet->setCellValue("K{$raw_material_row}", $raw_material_row_data->remark);

                        $event->sheet->getDelegate()
                            ->getStyle("A{$raw_material_row}:P{$raw_material_row}")
                            ->applyFromArray($border)
                            ->applyFromArray($font_8_arial)
                            ->getAlignment()
                            ->setWrapText(true);
                        
                        $event->sheet->getDelegate()
                            ->getStyle("A{$raw_material_row}:B{$raw_material_row}")
                            ->applyFromArray($text_align_left);
                        
                        $event->sheet->getDelegate()
                            ->getStyle("C{$raw_material_row}:E{$raw_material_row}")
                            ->applyFromArray($text_center);                
                        
                        $event->sheet->getDelegate()
                            ->getStyle("F{$raw_material_row}:P{$raw_material_row}")
                            ->applyFromArray($text_align_left);
                    }
            
                    $raw_material_start_row += $raw_material_invoice_count;
                    $rawMaterialInvoiceRecord += $raw_material_invoice_count - 1;
                }
            }
        }];
    }
}





