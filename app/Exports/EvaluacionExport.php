<?php

namespace App\Exports;
 
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
 
class EvaluacionExport implements FromView,WithTitle,WithEvents
{
 
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function view(): View
    {
        return view('inventario.reportes.vw_evaluaciones_ex', [
            'calificacion' => $this->data
        ]);
    }
    
    public function title(): string
    {
        return 'EVALUACIONES';
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->getSheet()->autoSize();
                $event->getSheet()->getDelegate()->getStyle('A3:K3')
                    ->getAlignment();
            },
            BeforeExport::class => function(BeforeExport $event) {
                $event->getWriter()->getDelegate()
                    ->getProperties()
                    ->setCreator("Enzo Edir Velásquez Lobatón")
                    ->setLastModifiedBy("Enzo Edir Velásquez Lobatón")
                    ->setTitle("SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES")
                    ->setSubject("SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES")
                    ->setDescription(
                        "EVALUACIÓN DE PROVEEDORES"
                    )
                ;
            }
        ];
    }
}