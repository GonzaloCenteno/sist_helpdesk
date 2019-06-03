<?php

namespace App\Exports;
 
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
 
class InventarioExport implements FromView,WithTitle,WithEvents
{
 
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function view(): View
    {
        return view('reportes.excel.vw_gestion_inventario_ex', [
            'datos' => $this->data,
        ]);
    }
    
    public function title(): string
    {
        return 'INVENTARIO';
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'B1:G4';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
 
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('img/img_cromotex/logo_cromotex_reporte_excel.png'));
                $drawing->setCoordinates('A1');

                $drawing->setWorksheet($event->sheet->getDelegate());
                $event->getSheet()->autoSize();
                $event->sheet->getStyle('G1:G4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'family' => 'Calibri'
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '454545'],
                        ],
                    ],
                ]);
                $event->sheet->getStyle('A5:G5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'family' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '454545'],
                        ],
                    ],
                ]);
                $event->sheet->getStyle('B1:F4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'family' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '454545'],
                        ],
                    ],
                ]);
                $event->getSheet()->getDelegate()->getStyle('A3:H3')
                ->getAlignment();
            },
            BeforeExport::class => function(BeforeExport $event) {
                $event->getWriter()->getDelegate()
                    ->getProperties()
                    ->setCreator("Enzo Edir Velásquez Lobatón")
                    ->setLastModifiedBy("Enzo Edir Velásquez Lobatón")
                    ->setTitle("GESTION Y MANTENIMIENTO DE INVENTARIO")
                    ->setSubject("GESTION Y MANTENIMIENTO DE INVENTARIO")
                    ->setDescription(
                        "GESTION DE INVENTARIO"
                    )
                ;
            }
        ];
    }
}