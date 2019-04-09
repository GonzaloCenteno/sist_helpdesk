<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>LISTA DE PROVEEDORES</title>
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
                            TC SGC-FT-006-01
                        </TD>
                    </TR>
                    <TR>
                        <TD style="text-align: center;">REVISION: 000-1</TD> 
                    </TR>
                    <TR>
                        <TD style="text-align: center;" ROWSPAN="2"><b>LISTA DE PROVEEDORES</b></TD> 
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
                        <td style="width: 20%; text-align: center;" colspan="2"><b>RESPONSABLE:</b></td>
                        <td style="width: 40%; text-align: center;" colspan="3"><b>Enzo Edir Velásquez Lobatón</b></td>
                        <td style="width: 10%; text-align: center;" colspan="2"><b>FIRMA:</b></td>
                        <td style="width: 10%; text-align: center;" colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="width: 20%; text-align: center;" colspan="2"><b>CARGO:</b></td>
                        <td style="width: 40%; text-align: center;" colspan="3"><b>Jefe de Sistemas</b></td>
                        <td style="width: 10%; text-align: center;" colspan="2"><b>FECHA DE ACTUALIZACIÓN:</b></td>
                        <td style="width: 10%; text-align: center;" colspan="2"></td>
                    </tr>
                    <tr style="font-size: 0.9em;">	
                        <td style="width: 5%; text-align: center;"><b>Nº DE REGISTRO (CODIGO)</b></td>
                        <td style="width: 25%; text-align: center;"><b>RAZON SOCIAL</b></td>
                        <td style="width: 2%; text-align: center;"><b>Nº RUC</b></td>
                        <td style="width: 25%; text-align: center;"><b>DIRECCIÓN</b></td>
                        <td style="width: 6%; text-align: center;"><b>PRODUCTO / SERVICIO</b></td>
                        <td style="width: 9%; text-align: center;"><b>PERSONA DE CONTACTO</b></td>
                        <td style="width: 8%; text-align: center;"><b>TELEFONO</b></td>
                        <td style="width: 10%; text-align: center;"><b>CORREO ELECTRÓNICO</b></td>
                        <td style="width: 5%; text-align: center;"><b>CATEGORÍA</b></td>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($proveedores as $prov)
                    <tr style="font-size: 0.8em;">
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;">{{ $prov->RAZPRO }}</td>
                        <td style="text-align: left;">{{ $prov->RUCPRO }}</td>
                        <td style="text-align: left;">{{ $prov->DIRPRO }}</td>
                        <td style="text-align: left;">{{ $prov->CONPRO }}</td>
                        <td style="text-align: left;">{{ $prov->SERPRO }}</td>
                        <td style="text-align: left;">{{ $prov->TELPRO }}</td>
                        <td style="text-align: left;">{{ $prov->CORRPRO }}</td>
                        <td style="text-align: left;"></td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </body>

</html>