<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CROMOAYUDA</title>

        <link rel="icon" type="image/png" href="{{ asset('img/bus-home.png') }}" />

        <style>
            #loader {
                transition: all 0.3s ease-in-out;
                opacity: 1;
                visibility: visible;
                position: fixed;
                height: 100vh;
                width: 100%;
                background: #fff;
                z-index: 90000;
            }

            #loader.fadeOut {
                opacity: 0;
                visibility: hidden;
            }

            .spinner {
                width: 40px;
                height: 40px;
                position: absolute;
                top: calc(50% - 20px);
                left: calc(50% - 20px);
                background-color: #333;
                border-radius: 100%;
                -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
                animation: sk-scaleout 1.0s infinite ease-in-out;
            }

            @-webkit-keyframes sk-scaleout {
                0% { -webkit-transform: scale(0) }
                100% {
                    -webkit-transform: scale(1.0);
                    opacity: 0;
                }
            }

            @keyframes sk-scaleout {
                0% {
                    -webkit-transform: scale(0);
                    transform: scale(0);
                } 100% {
                    -webkit-transform: scale(1.0);
                    transform: scale(1.0);
                    opacity: 0;
                }
            }
        </style>

        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('ckeditor/css/samples.css') }}" rel="stylesheet">
        <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">    
        <link href="{{ asset('css/smartadmin-production-plugins.min.css') }}" rel="stylesheet" type="text/css" media="screen">
        <link href="{{ asset('css/smartadmin-production.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-skins.min.css') }}" rel="stylesheet">
        <!--<link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">-->
        <link href="{{ asset('css/ui.jqgrid.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    </head>
    <body class="app" onload="dontBack();">
        <div id='loader'>
            <div class="spinner"></div>
        </div>

        <div>
            <!-- #Left Sidebar ==================== -->
            <div class="sidebar">
                <div class="sidebar-inner">
                    <!-- ### $Sidebar Header ### -->
                    <div class="sidebar-logo">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer peer-greed">
                                <a class="sidebar-link td-n" href="#">
                                    <div class="peers ai-c fxw-nw">
                                        <div class="peer">
                                            <div class="logo">
                                                <img src="{{ asset('img/cromohelp/logo.png') }}" alt="" style="width:265px">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="peer peer-greed text-center">
                                        <h4 class="lh-1 mB-0 logo-text">CromoAyuda</h4>
                                    </div>
                                </a>
                            </div>
                            <div class="peer">
                                <div class="mobile-toggle sidebar-toggle">
                                    <a href="" class="td-n">
                                        <i class="ti-arrow-circle-left"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ### $Sidebar Menu ### -->
                    <ul class="sidebar-menu scrollable pos-r">
                        @if(isset($tblmenu_men))  
                            @foreach($tblmenu_men as $menu)
                                <li class="nav-item mT-30 active">
                                    <a class="sidebar-link" href="{{ $menu->menu_rut }}">
                                        <span class="icon-holder">
                                            <i class="c-blue-500 ti-menu"></i>
                                        </span>
                                        <span class="title">{{ $menu->menu_desc }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                        @endif
                        
                        @if(session('rol') == 1 || session('rol') == 2)  
                        <hr>
                            @if(isset($tblmenu_men2))  
                                <li class="nav-item dropdown">
                                <a class="dropdown-toggle" href="javascript:void(0);">
                                    <span class="icon-holder">
                                        <i class="c-blue-500 ti-layout-list-thumb"></i>
                                    </span>
                                    <span class="title">Inventario</span>
                                    <span class="arrow">
                                        <i class="ti-angle-right"></i>
                                    </span>
                                </a>
                                    <ul class="dropdown-menu">
                                @foreach($tblmenu_men2 as $menu2)
                                    <li>
                                        <a class='sidebar-link' href="{{ $menu2->menu_rut }}">
                                            <span class="icon-holder">
                                                <i class="c-blue-500 ti-menu"></i>
                                            </span>
                                            <span class="title">{{ $menu2->menu_desc }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            @else
                            @endif
                                    </ul>
                                </li>    
                        @else
                        @endif
                        
                        @if(session('rol') == 1 || session('rol') == 2)  
                        <hr>
                            @if(isset($tblmenu_men3))  
                                <li class="nav-item dropdown">
                                <a class="dropdown-toggle" href="javascript:void(0);">
                                    <span class="icon-holder">
                                        <i class="c-blue-500 ti-layout-list-thumb"></i>
                                    </span>
                                    <span class="title">Encuesta</span>
                                    <span class="arrow">
                                        <i class="ti-angle-right"></i>
                                    </span>
                                </a>
                                    <ul class="dropdown-menu">
                                @foreach($tblmenu_men3 as $menu3)
                                    <li>
                                        <a class='sidebar-link' href="{{ $menu3->menu_rut }}">
                                            <span class="icon-holder">
                                                <i class="c-blue-500 ti-menu"></i>
                                            </span>
                                            <span class="title">{{ $menu3->menu_desc }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            @else
                            @endif
                                    </ul>
                                </li>    
                        @else
                        @endif
                    </ul>
                </div>
            </div>

            <!-- #Main ============================ -->
            <div class="page-container">
                <!-- ### $Topbar ### -->
                <div class="header navbar">
                    <div class="header-container">
                        <ul class="nav-left">
                            <li>
                                <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
                                    <i class="ti-menu"></i>
                                </a>
                            </li>
                            <li class="search-box">
                                <div class="form-control text-center"><h2 class="text-center">Punto de Venta: {{ session('desc_pvt') }}</h2></div>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="dropdown">
                                <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                                    <div class="peer mR-10">
                                        <img class="w-2r bdrs-50p" src="https://randomuser.me/api/portraits/men/10.jpg" alt="">
                                    </div>
                                    <div class="peer">
                                        <span class="fsz-sm c-grey-900">{{ session('nomb_usuario') }}</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu fsz-xs">
<!--                                    <li>
                                        <a href="" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                            <i class="ti-settings mR-10"></i>
                                            <span>Setting</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                            <i class="ti-user mR-10"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="email.html" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                            <i class="ti-email mR-10"></i>
                                            <span>Messages</span>
                                        </a>
                                    </li>-->
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <form method="GET" action="{{ route('logout') }}">
                                            {{ csrf_field() }}
                                            <a href="{{ route('logout') }}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                                <i class="ti-power-off mR-10"></i>
                                                <span>CERRAR SESION</span>
                                            </a>
                                        </form>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div style="display:none;">
                    <audio id="alerta_mensaje" controls>
                        <source type="audio/mp3" src="{{ asset('alertas/dota2.mp3') }}">
                    </audio>
                </div>

                <!-- ### $App Screen Content ### -->
                <main class='main-content bgc-grey-100' style="background-image:url('{{ asset('img/cromohelp/modelo2.jpg') }}');">
                    <div id='mainContent'>
                        @yield('content')
                    </div>
                </main>

                <!-- ### $App Screen Footer ### -->
                <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
                    <span>Copyright © 2019 Propiedad de: <a href="https://www.cromotex.com.pe/" target='_blank' title="Colorlib">Transportes Cromotex</a>. Todos los Derechos Reservados.</span>
                </footer>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>
        <script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bundle.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery.jqGrid.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/grid.locale-es.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/select2.full.min.js') }}"></script>
        <script src="https://js.pusher.com/4.2/pusher.min.js"></script>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
        <script type="text/javascript" src="{{ asset('archivos_js/funciones_globales.js') }}"></script>

        <script type="text/javascript">
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            setTimeout(() => {
                loader.classList.add('fadeOut');
            }, 300);
        });

        function dontBack() {
            window.location.hash = "";
            window.location.hash = "Again-No-back-button" //chrome
            window.onhashchange = function () {
                window.location.hash = "";
            }
        }
        
        </script>

        <script>
            var variable = {!! session('rol') !!};
            //alert(variable);
            var pusher = new Pusher('d8966da1d9f626630fe1', {
                cluster: 'us2',
                encrypted: true
            });

            if (variable == 1 || variable == 2)
            {
                var channel = pusher.subscribe('notify_user');
                channel.bind('notify-event_user', function (message) {
                    //alert(message);
                    jQuery("#tabla_asignar_tickets").jqGrid('setGridParam', {
                        url: 'ticketasignar/0?grid=asignar_tickets'
                    }).trigger('reloadGrid');

                    var alerta = document.getElementById("alerta_mensaje");
                    alerta.play();
                    console.log(message);
                });
            }
        </script>

        @yield('page-js-script')
    </body>
</html>
