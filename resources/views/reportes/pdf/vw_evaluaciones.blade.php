<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>EVALUACION DE PROVEEDORES</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
            .pagenum:after { content:' ' counter(page); }
            .footer {position: fixed }
            #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
        </style>
    </head>

    <body>
        <div class="header">
            <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                <thead>
                    <TR>
                        <TD style="width: 20%; text-align: center;" ROWSPAN="4">
                            <img src="img/img_cromotex/encabezado.png" height="75px"/>
                        </TD>
                        <TD style="width: 60%; text-align: center;" ROWSPAN="2"><b>SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES</b></TD>
                        <TD style="width: 20%; text-align: center;">CODIGO:<br>
                            TC SGC-FT-006-02
                        </TD>
                    </TR>
                    <TR>
                        <TD style="text-align: center;">REVISION: 000-1</TD> 
                    </TR>
                    <TR>
                        <TD style="text-align: center;" ROWSPAN="2"><b>EVALUACIÓN DE PROVEEDORES</b></TD> 
                        <TD style="text-align: center;">FECHA: {{ date('d-m-Y') }}</TD> 
                    </TR>
                    <TR>
                        <TD style="text-align: center;">
                            <script type="text/php">
                                if ( isset($pdf) ) {
                                    $font = $fontMetrics->getFont("Arial, sans-serif", "bold");
                                    $pdf->page_text(690, 96, "PÁGINA: {PAGE_NUM} DE {PAGE_COUNT}", $font, 12, array(0,0,0));
                                }
                            </script>
                        </TD> 
                    </TR>
                </thead>
            </table>
        </div>

        <input type="hidden" value=" {{$num= 1}}">

        <div class="lado3" style="height: 435px; margin-bottom: 20px;">
            <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                <thead>
                    <tr>
                        <td style="width: 20%; text-align: left;" colspan="2"><b>RESPONSABLE:</b></td>
                        <td style="width: 60%; text-align: left;" colspan="5"><b>Enzo Edir Velásquez Lobatón</td>
                        <td style="width: 20%; text-align: left;" colspan="3"><b>FIRMA:</b></td>
                        <td style="width: 10%; text-align: left;" colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="width: 20%; text-align: left;" colspan="2"><b>CARGO:</b></td>
                        <td style="width: 60%; text-align: left;" colspan="5"><b>Jefe de Sistemas</b></td>
                        <td style="width: 20%; text-align: left;" colspan="3"><b>FECHA DE ACTUALIZACIÓN:</b></td>
                        <td style="width: 10%; text-align: left;" colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-size: 0.6em;" colspan="12">
                            <b>Rango de criterio de PRECIO para asignación de puntaje:</b> Otras opciones = 1, 4ta opción = 2, 3ra opción = 3, 2da opción = 4, 1ra opción = 5  <br>
                            <b>Rango de criterio de CALIDAD, CRÉDITO para asignación de puntaje:</b> Deficiente= 1, Malo= 2, Regular= 3, Bueno= 4, Muy Bueno= 5  <br>
                            <b>Rango de puntaje de DISPONIBILIDAD DE STOCK y ENTREGA DE DOCUMENTOS para asignación de puntaje:</b> Más de 30 días = 1, 29a20 días = 2, 19a10 días = 3, 9a5 días = 4, Menos de 5 días = 5   <br>
                        </td>
                    </tr>
                    <tr style="font-size: 0.9em;">	
                        <td style="width: 2%; text-align: center;" rowspan="2"><b>N°</b></td>
                        <td style="width: 12%; text-align: center;" rowspan="2"><b>RUC</b></td>
                        <td style="width: 35%; text-align: center;" rowspan="2"><b>PRODUCTO / SERVICIO</b></td>
                        <td style="width: 10%; text-align: center;" rowspan="2"><b>PRECIO</b></td>
                        <td style="width: 10%; text-align: center;" rowspan="2"><b>FECHA DE SOLICITUD</b></td>
                        <td style="width: 10%; text-align: center;" rowspan="2"><b>FECHA DE ENTREGA</b></td>
                        <td style="width: 10%; text-align: center;" colspan="5"><b>PUNTUACIÓN</b></td>
                        <td style="width: 14%; text-align: center;" rowspan="2"><b>PUNTAJE TOTAL</b></td>
                    </tr>
                    <tr style="font-size: 0.9em;">	
                        <td style="width: 14%; text-align: center;"><b>PUNTAJE PRECIO</b></td>
                        <td style="width: 10%; text-align: center;"><b>CALIDAD</b></td>
                        <td style="width: 14%; text-align: center;"><b>DISPONIBILIDAD DE STOCK</b></td>
                        <td style="width: 10%; text-align: center;"><b>CRÉDITO</b></td>
                        <td style="width: 14%; text-align: center;"><b>ENTREGA DE DOCUMENTOS</b></td>
                    </tr>
                </thead>
                <tbody>
                    
                    @if($count == 1)
                        <tr>
                            <td style="text-align: center;">{{ $num++ }}</td>
                            <td style="text-align: center;">{{ $calificacion->RUCCAL }}</td>
                            <td style="text-align: left;">{{ $calificacion->DESCAL }}</td>
                            @if($calificacion->MONECAL == 0)
                                <td style="text-align: right;">S/ {{ round($calificacion->PRECAL,2) }}</td>
                            @else
                                <td style="text-align: right;">$ {{ round($calificacion->PRECAL,2) }}</td>
                            @endif
                            <td style="text-align: center;">{{ $calificacion->FSOLCAL }}</td>
                            <td style="text-align: center;">{{ $calificacion->FRECCAL }}</td>
                            <td style="text-align: center;">{{ $calificacion->PPRECAL }}</td>
                            <td style="text-align: center;">{{ $calificacion->CALCAL }}</td>
                            <td style="text-align: center;">{{ $calificacion->PSTOCAL }}</td>
                            <td style="text-align: center;">{{ $calificacion->PCRECAL }}</td>
                            <td style="text-align: center;">{{ $calificacion->PDOCCAL }}</td>
                            <td style="text-align: center;">{{ $calificacion->TOTAL }}</td>
                        </tr>
                    @else
                        @foreach ($calificacion as $cal)
                        <tr>
                            <td style="text-align: center;">{{ $num++ }}</td>
                            <td style="text-align: center;">{{ $cal->RUCCAL }}</td>
                            <td style="text-align: left;">{{ $cal->DESCAL }}</td>
                            @if($cal->MONECAL == 0)
                                <td style="text-align: right;">S/ {{ round($cal->PRECAL,2) }}</td>
                            @else
                                <td style="text-align: right;">$ {{ round($cal->PRECAL,2) }}</td>
                            @endif
                            <td style="text-align: center;">{{ $cal->FSOLCAL }}</td>
                            <td style="text-align: center;">{{ $cal->FRECCAL }}</td>
                            <td style="text-align: center;">{{ $cal->PPRECAL }}</td>
                            <td style="text-align: center;">{{ $cal->CALCAL }}</td>
                            <td style="text-align: center;">{{ $cal->PSTOCAL }}</td>
                            <td style="text-align: center;">{{ $cal->PCRECAL }}</td>
                            <td style="text-align: center;">{{ $cal->PDOCCAL }}</td>
                            <td style="text-align: center;">{{ $cal->TOTAL }}</td>
                        </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </body>

</html>