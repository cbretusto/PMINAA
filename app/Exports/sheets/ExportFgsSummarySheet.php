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



class ExportFgsSummarySheet implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    use Exportable;
    protected $data;
    protected $user;

    function __construct(
        $data,
        $user
    ){
        $this->data = $data;
        $this->user = $user;
    }

    public function view(): View {
        return view('exports.export_report', [
            'data' => $this->data,
            'user' => $this->user
        ]);
    }

    public function title(): string{
        return 'FGS Summary';
    }

    //for designs
    public function registerEvents(): array{
        $data = $this->data;
        $user = $this->user;

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
            $user,
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
                ->getStyle('A3:Q3')
                ->getFill()
                ->setFillType($excel)
                ->getStartColor()
                ->setARGB('9fc5e8');

            // $event->sheet->freezePane('');
            $event->sheet->getDelegate()->getStyle('A3:Q3')->applyFromArray($border);
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
            $event->sheet->getColumnDimension('L')->setWidth(35);
            $event->sheet->getColumnDimension('M')->setWidth(20);
            $event->sheet->getColumnDimension('N')->setWidth(20);
            $event->sheet->getColumnDimension('O')->setWidth(20);
            $event->sheet->getColumnDimension('P')->setWidth(20);
            $event->sheet->getColumnDimension('Q')->setWidth(20);
            
            $event->sheet
                ->getDelegate()
                ->mergeCells('A1:Q2');

            $event->sheet
                ->getDelegate()
                ->getStyle('A1')
                ->applyFromArray($text_center)
                ->applyFromArray($font_10_arial_bold)
                ->getAlignment()
                ->setWrapText(true);

            $event->sheet
                ->getDelegate()
                ->getStyle('A3:Q3')
                ->applyFromArray($text_center)
                ->applyFromArray($font_9_arial_bold)
                ->getAlignment()
                ->setWrapText(true);
            
            $event->sheet->setCellValue('A1',"FGS - REVISED SELLING PRICE");
            
            $event->sheet->setCellValue('A3',"  Invoice No.");
            $event->sheet->setCellValue('B3',"  Packing List \nControl No.");
            $event->sheet->setCellValue('C3',"  Invoice \nControl No.");
            $event->sheet->setCellValue('D3',"  Revised \nDate");
            $event->sheet->setCellValue('E3',"  Revision \nNo.");
            $event->sheet->setCellValue('F3',"  PO \nNumber");
            $event->sheet->setCellValue('G3',"  PO \nQuantity");
            $event->sheet->setCellValue('H3',"  Product \nCode");
            $event->sheet->setCellValue('I3',"  Product \nName");
            $event->sheet->setCellValue('J3',"  New \nPrice");
            $event->sheet->setCellValue('K3',"  Old \nPrice");
            $event->sheet->setCellValue('L3',"  Remark");
            $event->sheet->setCellValue('M3',"  Requested By");
            $event->sheet->setCellValue('N3',"  Checked By");
            $event->sheet->setCellValue('O3',"  Noted By");
            $event->sheet->setCellValue('P3',"  Approved By");
            $event->sheet->setCellValue('Q3',"  Conformed By");

            $fgs_start_row = 4;
            $fgs_invoice_data = count($data); 
            for ($fgsInvoiceRecord = 0; $fgsInvoiceRecord < $fgs_invoice_data; $fgsInvoiceRecord++) {
                $fgs_merge_data = $data[$fgsInvoiceRecord];
                $fgs_invoice_info = $fgs_merge_data->invoicing_info;
            
                if ($fgs_invoice_info != null) {
                    $fgs_invoice_to_merge = $fgs_invoice_info->invoice_no . '|' . $fgs_invoice_info->transaction_no;
                    $fgs_invoice_count = 1;
            
                    // Count how many rows to merge for invoice
                    for ($fgsInvoiceMergeLoop = $fgsInvoiceRecord + 1; $fgsInvoiceMergeLoop < $fgs_invoice_data; $fgsInvoiceMergeLoop++) {
                        $fgs_merge_data = $data[$fgsInvoiceMergeLoop]->invoicing_info;
                        if ($fgs_merge_data != null) {
                            $fgs_next_row = $fgs_merge_data->invoice_no . '|' . $fgs_merge_data->transaction_no;
                            if ($fgs_next_row === $fgs_invoice_to_merge) {
                                $fgs_invoice_count++;
                            } else {
                                break;
                            }
                        }
                    }
            
                    $fgs_invoice_end_row = $fgs_start_row + $fgs_invoice_count - 1;
            
                    if ($fgs_invoice_count > 1) {
                        $event->sheet->mergeCells("A{$fgs_start_row}:A{$fgs_invoice_end_row}");
                        $event->sheet->mergeCells("B{$fgs_start_row}:B{$fgs_invoice_end_row}");
                        // $event->sheet->mergeCells("C{$fgs_start_row}:C{$fgs_invoice_end_row}");
                    }
            
                    $event->sheet->setCellValue("A{$fgs_start_row}", $fgs_invoice_info->invoice_no);
                    $event->sheet->setCellValue("B{$fgs_start_row}", $fgs_invoice_info->packing_list_ctrl);
                    // $event->sheet->setCellValue("C{$fgs_start_row}", $fgs_invoice_info->packing_list_ctrl);

                    $fgs_revision_row = 0;
                    while ($fgs_revision_row < $fgs_invoice_count) {
                        $fgs_revision_start_row = $fgs_start_row + $fgs_revision_row;
                        $fgs_current_revision = $data[$fgsInvoiceRecord + $fgs_revision_row]->revision_no;
                        $fgs_revision_count = 1;

                        while (($fgs_revision_row + $fgs_revision_count) < $fgs_invoice_count &&
                            $data[$fgsInvoiceRecord + $fgs_revision_row + $fgs_revision_count]->revision_no === $fgs_current_revision) {
                            $fgs_revision_count++;
                        }

                        $fgs_revision_end_row = $fgs_revision_start_row + $fgs_revision_count - 1;

                        if ($fgs_revision_count > 1) {
                            $event->sheet->mergeCells("C{$fgs_revision_start_row}:C{$fgs_revision_end_row}");
                            $event->sheet->mergeCells("D{$fgs_revision_start_row}:D{$fgs_revision_end_row}");
                            $event->sheet->mergeCells("E{$fgs_revision_start_row}:E{$fgs_revision_end_row}");

                            $event->sheet->mergeCells("M{$fgs_revision_start_row}:M{$fgs_revision_end_row}");
                            $event->sheet->mergeCells("N{$fgs_revision_start_row}:N{$fgs_revision_end_row}");
                            $event->sheet->mergeCells("O{$fgs_revision_start_row}:O{$fgs_revision_end_row}");
                            $event->sheet->mergeCells("P{$fgs_revision_start_row}:P{$fgs_revision_end_row}");
                            $event->sheet->mergeCells("Q{$fgs_revision_start_row}:Q{$fgs_revision_end_row}");
                        }

                        $event->sheet->setCellValue("C{$fgs_revision_start_row}", $data[$fgsInvoiceRecord + $fgs_revision_row]->control_no);
                        $event->sheet->setCellValue("D{$fgs_revision_start_row}", $fgs_current_revision);
                        $event->sheet->setCellValue("E{$fgs_revision_start_row}", $data[$fgsInvoiceRecord + $fgs_revision_row]->revised_date);

                        $approver = $data[$fgsInvoiceRecord + $fgs_revision_row]->invoicing_info->invoice_approver_details;
                        for ($i=0; $i < count($approver); $i++) { 
                            if($fgs_current_revision == $approver[$i]->revision_no){
                                // $requestedBy = json_decode($approver[$i]->requested_by, true);
                                // for ($ii = 0; $ii < count($approver); $ii++) {
                                //     $requestedBy = json_decode($approver[$ii]->requested_by, true); // convert JSON to array
                                //     for ($iii = 0; $iii < count($user); $iii++) {
                                //         if (in_array($user[$iii]->id, $requestedBy)) {
                                //             dd($user[$iii]->name);
                                //         }
                                //     }
                                // }
                                
                                // $requestedBy = json_decode($approver[$i]->checked_by, true);
                                // for ($iv = 0; $iv < count($approver); $iv++) {
                                //     $requestedBy = json_decode($approver[$iv]->checked_by, true); // convert JSON to array
                                //     for ($v = 0; $v < count($user); $v++) {
                                //         if (in_array($user[$v]->id, $requestedBy)) {
                                //             dd($user[$v]->name);
                                //         }
                                //     }
                                // }
                                // $event->sheet->setCellValue("M{$fgs_revision_start_row}", '');
                                // $event->sheet->setCellValue("N{$fgs_revision_start_row}", '');

                                $requestedNames = [];
                                $requestedBy = json_decode($approver[$i]->requested_by, true);
                                foreach ($user as $u) {
                                    if (in_array($u->id, $requestedBy)) {
                                        $requestedNames[] = $u->name;
                                    }
                                }

                                $checkedNames = [];
                                $checkedBy = json_decode($approver[$i]->checked_by, true);

                                foreach ($user as $u) {
                                    if (in_array($u->id, $checkedBy)) {
                                        $checkedNames[] = $u->name;
                                    }
                                }

                                $event->sheet->setCellValue("M{$fgs_revision_start_row}", implode("\n", $requestedNames));
                                $event->sheet->setCellValue("N{$fgs_revision_start_row}", implode("\n", $checkedNames));
                                $event->sheet->setCellValue("O{$fgs_revision_start_row}", $approver[$i]->approver_noted_by_info->name);
                                $event->sheet->setCellValue("P{$fgs_revision_start_row}", $approver[$i]->approver_approved_by_info->name);
                                $event->sheet->setCellValue("Q{$fgs_revision_start_row}", $approver[$i]->approver_conformed_by_info->name);
                            }
                        }

                        $fgs_revision_row += $fgs_revision_count;
                    }
            
                    for ($invoiceGroup = 0; $invoiceGroup < $fgs_invoice_count; $invoiceGroup++) {
                        $fgs_row = $fgs_start_row + $invoiceGroup;
                        $fgs_row_data = $data[$fgsInvoiceRecord + $invoiceGroup];

                        $event->sheet->setCellValue("F{$fgs_row}", $fgs_row_data->po_no);
                        $event->sheet->getDelegate()->getStyle("F{$fgs_row}")->getNumberFormat()->setFormatCode('0');
                        $event->sheet->setCellValue("G{$fgs_row}", $fgs_row_data->po_qty);
                        $event->sheet->setCellValue("H{$fgs_row}", $fgs_row_data->product_code);
                        $event->sheet->setCellValue("I{$fgs_row}", $fgs_row_data->product_name);
                        $event->sheet->setCellValue("J{$fgs_row}", $fgs_row_data->price_from);
                        $event->sheet->setCellValue("K{$fgs_row}", $fgs_row_data->price_to);
                        $event->sheet->setCellValue("L{$fgs_row}", $fgs_row_data->remark);

                        $event->sheet->getDelegate()
                            ->getStyle("A{$fgs_row}:Q{$fgs_row}")
                            ->applyFromArray($border)
                            ->applyFromArray($font_8_arial)
                            ->getAlignment()
                            ->setWrapText(true);
            
                        $event->sheet->getDelegate()
                            ->getStyle("A{$fgs_row}:B{$fgs_row}")
                            ->applyFromArray($text_align_left);
            
                        $event->sheet->getDelegate()
                            ->getStyle("C{$fgs_row}:E{$fgs_row}")
                            ->applyFromArray($text_center);
            
                        $event->sheet->getDelegate()
                            ->getStyle("F{$fgs_row}:L{$fgs_row}")
                            ->applyFromArray($text_align_left);

                        $event->sheet->getDelegate()
                            ->getStyle("M{$fgs_row}:Q{$fgs_row}")
                            ->applyFromArray($text_center);
                    }
            
                    $fgs_start_row += $fgs_invoice_count;
                    $fgsInvoiceRecord += $fgs_invoice_count - 1;
                }
            }
            
        }];
    }
}
