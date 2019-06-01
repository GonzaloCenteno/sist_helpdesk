@extends('principal.p_inicio')

@section('content')
<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item  w-100">
        <div class="row gap-20">
            <!-- #Toatl Visits ==================== -->
            <div class='col-md-6'>
                <div class="layers bd bgc-white p-10">
                    <div class="layer w-100 mB-10">
                        <h5 class="lh-1">APERTURADOS</h5>
                    </div>
                    <div class="layer w-100">
                        <div class="peers ai-sb fxw-nw">
                            <div class="peer peer-greed">
                                <span id="sparklinedash"></span>
                            </div>
                            <div class="peer">
                                <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-30 pY-25 bgc-green-50 c-black-500">{{ $aperturados }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- #Total Page Views ==================== -->
            <div class='col-md-6'>
                <div class="layers bd bgc-white p-10">
                    <div class="layer w-100 mB-10">
                        <h5 class="lh-1">EN PROCESO</h5>
                    </div>
                    <div class="layer w-100">
                        <div class="peers ai-sb fxw-nw">
                            <div class="peer peer-greed">
                                <span id="sparklinedash2"></span>
                            </div>
                            <div class="peer">
                                <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-30 pY-25 bgc-purple-50 c-black-500">{{ $proceso }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- #Unique Visitors ==================== -->
            <div class='col-md-6'>
                <div class="layers bd bgc-white p-10">
                    <div class="layer w-100 mB-10">
                        <h5 class="lh-1">FINALIZADOS</h5>
                    </div>
                    <div class="layer w-100">
                        <div class="peers ai-sb fxw-nw">
                            <div class="peer peer-greed">
                                <span id="sparklinedash3"></span>
                            </div>
                            <div class="peer">
                                <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-30 pY-25 bgc-blue-50 c-black-500">{{ $finalizado }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- #Bounce Rate ==================== -->
            <div class='col-md-6'>
                <div class="layers bd bgc-white p-10">
                    <div class="layer w-100 mB-10">
                        <h5 class="lh-1">TOTAL</h5>
                    </div>
                    <div class="layer w-100">
                        <div class="peers ai-sb fxw-nw">
                            <div class="peer peer-greed">
                                <span id="sparklinedash4"></span>
                            </div>
                            <div class="peer">
                                <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-30 pY-25 bgc-red-50 c-black-500">{{ $total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('page-js-script')
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    if (variable == 1 || variable == 2)
    {
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function () {
            OneSignal.init({
                appId: "e15317a4-06ae-422c-919d-eade16bf4608",
            });
        });
    }
</script>
@stop
@endsection


