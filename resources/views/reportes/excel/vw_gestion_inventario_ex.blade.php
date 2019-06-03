<table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
    <thead>
        <tr>
            <td style="width: 20%; text-align: center; background-color: #C32227;" ROWSPAN="4">
                <img src="img/img_cromotex/logo_cromotex_reporte.png" height="75px"/>
            </td>
            <td style="width: 60%; text-align: center;" ROWSPAN="2" colspan="4"><b>PROCESO DE TI</b></td>
            <td style="width: 8%; text-align: left; font-size: 0.6em;">CODIGO:</td>
            <td style="width: 14%; text-align: left; font-size: 0.6em;">TC TI-PR 10 01</td>
        </tr>
        <tr>
            <td style="text-align: left; font-size: 0.6em;">REVISION:</td> 
            <td style="text-align: left; font-size: 0.6em;">0</td> 
        </tr>
        <tr>
            <td style="text-align: center;" ROWSPAN="2" colspan="4"><b>GESTION Y MANTENIMIENTO DE INVENTARIO</b></td> 
            <td style="text-align: left; font-size: 0.6em;">ACTUALIZACION:</td> 
            <td style="text-align: left; font-size: 0.6em;">xx/xx/xxxx</td> 
        </tr>
        <tr>
            <td style="text-align: left; font-size: 0.6em;">PAGINA:</td> 
            <td style="text-align: left; font-size: 0.6em;">
                <script type="text/php">
                    1 DE 1
                </script>
            </td> 
        </tr>
        <tr>
            <td style="width: 15%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>SERIE</b></td>
            <td style="width: 25%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>DESCRIPCION</b></td>
            <td style="width: 5%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>CANTIDAD</b></td>
            <td style="width: 5%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>MARCA</b></td>
            <td style="width: 25%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>PROVEEDOR - RAZON SOCIAL</b></td>
            <td style="width: 10%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>PROVEEDOR - RUC</b></td>
            <td style="width: 10%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>PROVEEDOR - TELEFONO</b></td>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $dat)
        <tr style="font-size: 0.9em;">
            <td style="text-align: left; font-size: 0.6em;">{{ $dat->item_ser }}</td>
            <td style="text-align: left; font-size: 0.6em;">{{ $dat->item_desc }}</td>
            <td style="text-align: center; font-size: 0.6em;">{{ $dat->item_cant }}</td>
            <td style="text-align: left; font-size: 0.6em;">{{ $dat->mar_desc }}</td>
            <td style="text-align: left; font-size: 0.6em;">{{ $dat->pro_raz }}</td>
            <td style="text-align: center; font-size: 0.6em;">{{ $dat->pro_ruc }}</td>
            <td style="text-align: center; font-size: 0.6em;">{{ $dat->pro_tel }}</td>
        </tr>
        @endforeach
    </tbody>
</table>