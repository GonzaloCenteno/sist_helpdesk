<input type="hidden" value=" {{$num= 1}}">
<table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
    <thead>
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
