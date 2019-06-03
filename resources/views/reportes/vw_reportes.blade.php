@extends('principal.p_inicio')

@section('content')
<style>
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }      
    .main-timeline{
        font-family: 'Roboto', sans-serif;
        padding: 20px 0;
        position: relative;
    }
    .main-timeline:before,
    .main-timeline:after{
        content: '';
        height: 40px;
        width: 40px;
        background-color: #e7e7e7;
        border-radius: 50%;
        border: 10px solid #303334;
        transform:translateX(-50%);
        position: absolute;
        left: 50%;
        top: -15px;
        z-index: 2;
    }
    .main-timeline:after{
        top: auto;
        bottom:15px;
    }
    .main-timeline .timeline{
        padding: 35px 0;
        margin-top: -30px;
        position: relative;
        z-index: 1;
    }
    .main-timeline .timeline:before,
    .main-timeline .timeline:after{
        content: '';
        height: 100%;
        width: 50%;
        border-radius: 100px 0 0 100px;
        border: 15px solid rgba(197, 218, 236, 0.3);
        border-right: none;
        position: absolute;
        left: 0;
        top: 0;
        z-index: -1;
    }
    .main-timeline .timeline:after{
        height: calc(100% - 30px);
        width: calc(50% - 12px);
        border-color: rgba(197, 218, 236, 0.3);
        left: 12px;
        top: 15px;
    }
    .main-timeline .timeline-content{ display:inline-block; }
    .main-timeline .timeline-content:hover{ text-decoration: none; }
    .main-timeline .timeline-year{
        color: rgba(7, 22, 23, 0.77);
        font-size: 50px;
        font-weight: 600;
        display: inline-block;
        transform:translateY(-50%);
        position: absolute;
        top: 50%;
        left: 10%;
    }
    .main-timeline .timeline-icon{
        color: rgba(7, 22, 23, 0.77);
        font-size: 80px;
        display: inline-block;
        transform: translateY(-50%);
        position: absolute;
        left: 34%;
        top: 50%;
    }
    .main-timeline .content{
        color: #909090;
        width: 50%;
        padding: 20px;
        display: inline-block;
        float: right;
    }
    .main-timeline .title{
        color: black;
        font-size: 22px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0 0 5px 0;
    }
    .main-timeline .description{
        font-size: 15px;
        letter-spacing: 1px;
        margin: 0;
    }
    .main-timeline .timeline:nth-child(even):before{
        left: auto;
        right: 0;
        border-radius: 0 100px 100px 0;
        border: 15px solid red;
        border-left: none;
    }
    .main-timeline .timeline:nth-child(even):after{
        left: auto;
        right: 12px;
        border: 15px solid green;
        border-left: none;
        border-radius: 0 100px 100px 0;
    }
    .main-timeline .timeline:nth-child(even) .content{ float: left; }
    .main-timeline .timeline:nth-child(even) .timeline-year{
        left: auto;
        right: 10%;
    }
    .main-timeline .timeline:nth-child(even) .timeline-icon{
        left: auto;
        right: 32%;
    }
    .main-timeline .timeline:nth-child(5n+2):before{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+2):after{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+2) .timeline-icon{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+2) .timeline-year{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+2) .title{ color: black }
    .main-timeline .timeline:nth-child(5n+3):before{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+3):after{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+3) .timeline-icon{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+3) .timeline-year{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+3) .title{ color: black; }
    .main-timeline .timeline:nth-child(5n+4):before{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+4):after{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+4) .timeline-icon{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+4) .timeline-year{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+4) .title{ color: black; }
    .main-timeline .timeline:nth-child(5n+5):before{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+5):after{ border-color: rgba(197, 218, 236, 0.3); }
    .main-timeline .timeline:nth-child(5n+5) .timeline-icon{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+5) .timeline-year{ color: rgba(7, 22, 23, 0.77); }
    .main-timeline .timeline:nth-child(5n+5) .title{ color: black; }
    @media screen and (max-width:1200px){
        .main-timeline .timeline:after{ border-radius: 88px 0 0 88px; }
        .main-timeline .timeline:nth-child(even):after{ border-radius: 0 88px 88px 0; }
    }
    @media screen and (max-width:767px){
        .main-timeline .timeline{ margin-top: -19px; }
        .main-timeline .timeline:before {
            border-radius: 50px 0 0 50px;
            border-width: 10px;
        }
        .main-timeline .timeline:after {
            height: calc(100% - 18px);
            width: calc(50% - 9px);
            border-radius: 43px 0 0 43px;
            border-width:10px;
            top: 9px;
            left: 9px;
        }
        .main-timeline .timeline:nth-child(even):before {
            border-radius: 0 50px 50px 0;
            border-width: 10px;
        }
        .main-timeline .timeline:nth-child(even):after {
            height: calc(100% - 18px);
            width: calc(50% - 9px);
            border-radius: 0 43px 43px 0;
            border-width: 10px;
            top: 9px;
            right: 9px;
        }
        .main-timeline .timeline-icon{ font-size: 60px; }
        .main-timeline .timeline-year{ font-size: 40px; }
    }
    @media screen and (max-width:479px){
        .main-timeline .timeline-icon{
            font-size: 50px;
            transform:translateY(0);
            top: 25%;
            left: 10%;
        }
        .main-timeline .timeline-year{
            font-size: 25px;
            transform:translateY(0);
            top: 65%;
            left: 9%;
        }
        .main-timeline .content{
            width: 68%;
            padding: 10px;
        }
        .main-timeline .title{ font-size: 18px; }
        .main-timeline .timeline:nth-child(even) .timeline-icon{
            right: 10%;
        }
        .main-timeline .timeline:nth-child(even) .timeline-year{
            right: 9%;
        }
    }
</style>
<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item col-md-12">
        <div class="bgc-white p-20 bd">
            <h2 class="c-grey-900 text-center">REPORTES GERENCIALES</h2>
            <div class="mT-30" style="padding-bottom: 499px;">
                <div class="form-row">
                    <div class="container">
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-timeline">
                                    <div class="timeline">
                                        @if( $permiso[0]->btn_print == 1 )
                                            <a href="#" class="timeline-content" data-toggle="modal" data-target="#Modal_Rep_01" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block">
                                            <span class="timeline-year">N° 1</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-list-alt"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">LISTA DE PROVEEDORES</h3>
                                                    <p class="description">
                                                        SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="#" onclick="sin_permiso();" class="timeline-content">
                                                <span class="timeline-year">N° 1</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-list-alt"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">LISTA DE PROVEEDORES</h3>
                                                    <p class="description">
                                                        SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="timeline">
                                        @if( $permiso[0]->btn_print == 1 )
                                            <a href="#" id="btn_imprimir_rep_02" data-toggle="modal" data-target="#Modal_Rep_02" data-backdrop="static" data-keyboard="false" class="timeline-content">
                                                <span class="timeline-year">N° 2</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-check-square-o"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">EVALUACION DE PROVEEDORES</h3>
                                                    <p class="description">
                                                        SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="#" onclick="sin_permiso();" class="timeline-content">
                                                <span class="timeline-year">N° 2</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-check-square-o"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">EVALUACION DE PROVEEDORES</h3>
                                                    <p class="description">
                                                        SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="timeline">
                                        @if( $permiso[0]->btn_print == 1 )
                                            <a href="#" id="btn_imprimir_rep_03" data-toggle="modal" data-target="#Modal_Rep_03" data-backdrop="static" data-keyboard="false" class="timeline-content">
                                                <span class="timeline-year">N° 3</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">REGISTRO DE GESTIÓN DE INCIDENTES</h3>
                                                    <p class="description">
                                                        REGISTRO DE GESTIÓN DE INCIDENTES,PROBLEMAS Y EVENTOS DE TI.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="#" onclick="sin_permiso();" class="timeline-content">
                                                <span class="timeline-year">N° 3</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">REGISTRO DE GESTIÓN DE INCIDENTES</h3>
                                                    <p class="description">
                                                        REGISTRO DE GESTIÓN DE INCIDENTES,PROBLEMAS Y EVENTOS DE TI.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="timeline">
                                        @if( $permiso[0]->btn_print == 1 )
                                            <a href="#" id="btn_imprimir_rep_04" data-toggle="modal" data-target="#Modal_Rep_04" data-backdrop="static" data-keyboard="false" class="timeline-content">
                                                <span class="timeline-year">N° 4</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-file-text-o"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI</h3>
                                                    <p class="description">
                                                        REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="#" onclick="sin_permiso();" class="timeline-content">
                                                <span class="timeline-year">N° 4</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-file-text-o"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI</h3>
                                                    <p class="description">
                                                        REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="timeline">
                                        @if( $permiso[0]->btn_print == 1 )
                                            <a href="#" id="btn_imprimir_rep_05" data-toggle="modal" data-target="#Modal_Rep_05" data-backdrop="static" data-keyboard="false" class="timeline-content">
                                                <span class="timeline-year">N° 5</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-bar-chart"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">GESTION DE INVENTARIO</h3>
                                                    <p class="description">
                                                        GESTION Y MANTENIMIENTO DE INVENTARIO.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="#" onclick="sin_permiso();" class="timeline-content">
                                                <span class="timeline-year">N° 5</span>
                                                <div class="timeline-icon">
                                                    <i class="fa fa-bar-chart"></i>
                                                </div>
                                                <div class="content text-center">
                                                    <h3 class="title">REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI</h3>
                                                    <p class="description">
                                                        GESTION Y MANTENIMIENTO DE INVENTARIO.<small style="color:#ffffff"> LISTA DE PROVEEDORES - SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES.REEVALUACIÓN DE PROVEEDORES.</small>
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL REP 01 -->
<div class="modal fade" id="Modal_Rep_01" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> LISTA DE PROVEEDORES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="imprimir_reporte_01(1);"><i class="fa fa-file-pdf-o"></i> IMPRIMIR PDF</button>
                <button type="button" class="btn btn-success" onclick="imprimir_reporte_01(2);"><i class="fa fa-file-excel-o"></i> IMPRIMIR EXCEL</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_rep_01"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL REP 02 -->
<div class="modal fade" id="Modal_Rep_02" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EVALUACION DE PROVEEDORES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row" id="FormularioRdbtn">
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA INICIO:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_inicio" placeholder="SELECCIONAR FECHA INICIO" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA FIN:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_fin" placeholder="SELECCIONAR FECHA FIN" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="imprimir_reporte_02(1);"><i class="fa fa-file-pdf-o"></i> IMPRIMIR PDF</button>
                <button type="button" class="btn btn-success" onclick="imprimir_reporte_02(2);"><i class="fa fa-file-excel-o"></i> IMPRIMIR EXCEL</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_rep_02"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL REP 03 -->
<div class="modal fade" id="Modal_Rep_03" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">REGISTRO DE GESTIÓN DE INCIDENTES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row" id="FormularioRdbtn">
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA INICIO:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_inicio_rep_03" placeholder="SELECCIONAR FECHA INICIO" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA FIN:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_fin_rep_03" placeholder="SELECCIONAR FECHA FIN" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="imprimir_reporte_03(1);"><i class="fa fa-file-pdf-o"></i> IMPRIMIR PDF</button>
                <button type="button" class="btn btn-success" onclick="imprimir_reporte_03(2);"><i class="fa fa-file-excel-o"></i> IMPRIMIR EXCEL</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_rep_03"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL REP 04 -->
<div class="modal fade" id="Modal_Rep_04" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">REGISTRO DE CALIFICACIÓN DE LA ATENCIÓN DE TI</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row" id="FormularioRdbtn">
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA INICIO:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_inicio_rep_04" placeholder="SELECCIONAR FECHA INICIO" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA FIN:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_fin_rep_04" placeholder="SELECCIONAR FECHA FIN" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="imprimir_reporte_04(1);"><i class="fa fa-file-pdf-o"></i> IMPRIMIR PDF</button>
                <button type="button" class="btn btn-success" onclick="imprimir_reporte_04(2);"><i class="fa fa-file-excel-o"></i> IMPRIMIR EXCEL</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_rep_04"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL REP 05 -->
<div class="modal fade" id="Modal_Rep_05" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">GESTION Y MANTENIMIENTO DE INVENTARIO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row" id="FormularioRdbtn">
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA INICIO:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_inicio_rep_05" placeholder="SELECCIONAR FECHA INICIO" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA FIN:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_print_fec_fin_rep_05" placeholder="SELECCIONAR FECHA FIN" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="imprimir_reporte_05(1);"><i class="fa fa-file-pdf-o"></i> IMPRIMIR PDF</button>
                <button type="button" class="btn btn-success" onclick="imprimir_reporte_05(2);"><i class="fa fa-file-excel-o"></i> IMPRIMIR EXCEL</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_rep_05"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/reportes/reportes.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
</script>
@stop
@endsection
