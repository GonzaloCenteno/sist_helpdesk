<table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
    <thead>
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
