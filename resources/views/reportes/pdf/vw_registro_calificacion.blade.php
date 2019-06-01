<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI</title>
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
                    <tr>
                        <td style="width: 20%; text-align: center; background-color: #C32227;" ROWSPAN="4">
                            <img src="img/img_cromotex/logo_cromotex_reporte.png" height="75px"/>
                        </td>
                        <td style="width: 60%; text-align: center;" ROWSPAN="2" colspan="{{ count($preguntas) + 1 }}"><b>PROCESO DE TI</b></td>
                        <td style="width: 8%; text-align: left; font-size: 0.6em;">CODIGO:</td>
                        <td style="width: 14%; text-align: left; font-size: 0.6em;">TC TI-PR 10 02</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size: 0.6em;">REVISION:</td> 
                        <td style="text-align: left; font-size: 0.6em;">0</td> 
                    </tr>
                    <tr>
                        <td style="text-align: center;" ROWSPAN="2" colspan="{{ count($preguntas) + 1 }}"><b>REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI</b></td> 
                        <td style="text-align: left; font-size: 0.6em;">ACTUALIZACION:</td> 
                        <td style="text-align: left; font-size: 0.6em;">xx/xx/xxxx</td> 
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size: 0.6em;">PAGINA:</td> 
                        <td style="text-align: left; font-size: 0.6em;">
                            <script type="text/php">
                                if ( isset($pdf) ) {
                                    $font = $fontMetrics->getFont("Arial, sans-serif", "bold");
                                    $pdf->page_text(742 + {{ count($preguntas) }}, 85, "{PAGE_NUM} DE {PAGE_COUNT}", $font, 8, array(0,0,0));
                                }
                            </script>
                        </td> 
                    </tr>
                    <tr>
                        <td style="width: 5%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>CODIGO</b></td>
                        <td style="width: 30%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>ASUNTO</b></td>
                        <td style="width: 15%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>TECNICO</b></td>
                        <td style="width: 10%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>USUARIO</b></td>
                        @foreach($preguntas as $preg)
                            <td style="width: 10%; text-align: center; background-color: #B3C6E7; font-size: 0.5em;"><b>{{ $preg->pre_desc }}</b></td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ( $datos as $dat ) {
                        ?>
                        <tr>
                        <?php
                        foreach ( $dat as $da ) {
                        ?>
                                <td style="text-align: center;"><?php echo isset($da) ? $da : 0;?></td>
                        <?php
                        }
                        ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>

</html>