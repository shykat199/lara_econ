<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="transparent" data-width="default" data-menu-styles="light" data-toggled="close">


<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Mamix - Laravel Bootstrap 5 Premium Admin & Dashboard Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="laravel template, admin panel laravel, laravel, laravel framework, laravel dashboard, admin, laravel admin, admin template, laravel developer, admin panel, bootstrap in laravel, admin dashboard, dashboard, admin panel template, template dashboard">

    <!-- TITLE -->
    <title>{{ config('app.name') }} - @yield('title') </title>

    <!-- FAVICON -->
    <link rel="icon" href="{{asset('build/assets/images/brand-logos/favicon.ico')}}" type="image/x-icon">

    <!-- BOOTSTRAP CSS -->
    <link  id="style" href="{{asset('build/assets/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- APP SCSS -->
    <link rel="preload" as="style" href="{{asset('build/assets/app-dDDo_cMZ.css')}}" />
    <link rel="stylesheet" href="{{asset('build/assets/app-dDDo_cMZ.css')}}" />

    <!-- ICONS CSS -->
    <link href="{{asset('build/assets/icon-fonts/icons.css')}}" rel="stylesheet">

    <!-- NODE WAVES CSS -->
    <link href="{{asset('build/assets/libs/node-waves/waves.min.css')}}" rel="stylesheet">

    <!-- SIMPLEBAR CSS -->
    <link rel="stylesheet" href="{{asset('build/assets/libs/simplebar/simplebar.min.css')}}">

    <!-- PICKER CSS -->
    <link rel="stylesheet" href="{{asset('build/assets/libs/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('build/assets/libs/%40simonwep/pickr/themes/nano.min.css')}}">

    <!-- AUTO COMPLETE CSS -->
    <link rel="stylesheet" href="{{asset('build/assets/libs/%40tarekraafat/autocomplete.js/css/autoComplete.css')}}">

    <!-- CHOICES CSS -->
    <link rel="stylesheet" href="{{asset('build/assets/libs/choices.js/public/assets/styles/choices.min.css')}}">

    <!-- CHOICES JS -->
    <script src="{{asset('build/assets/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
    <!-- MAIN JS -->
    <script src="{{asset('build/assets/main.js')}}"></script>

@stack('css')


</head>

<body>

<!-- LOADER -->
<div id="loader">
    <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
</div>
<!-- END LOADER -->

<!-- PAGE -->
<div class="page">

    <!-- HEADER -->

    @include('admin.layout.header')

    <div class="modal fade" id="header-responsive-search" tabindex="-1" aria-labelledby="header-responsive-search" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control border-end-0" placeholder="Search Anything ..."
                               aria-label="Search Anything ..." aria-describedby="button-addon2">
                        <button class="btn btn-primary" type="button"
                                id="button-addon2"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END HEADER -->

    <!-- SIDEBAR -->

    @include('admin.layout.sidebar')
    <!-- END SIDEBAR -->

    <!-- MAIN-CONTENT -->

    <div class="main-content app-content">
        @section('content')

        @show
    </div>

    <!-- END MAIN-CONTENT -->

    <!-- FOOTER -->

    @include('admin.layout.footer')
    <!-- END FOOTER -->

</div>
<!-- END PAGE-->

<!-- SCRIPTS -->

<!-- SCROLL-TO-TOP -->
<div class="scrollToTop">
    <span class="arrow lh-1"><i class="ti ti-caret-up fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>

<!-- POPPER JS -->
<script src="{{asset('build/assets/libs/%40popperjs/core/umd/popper.min.js')}}"></script>

<!-- BOOTSTRAP JS -->
<script src="{{asset('build/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- NODE WAVES JS -->
<script src="{{asset('build/assets/libs/node-waves/waves.min.js')}}"></script>

<!-- SIMPLEBAR JS -->
<script src="{{asset('build/assets/libs/simplebar/simplebar.min.js')}}"></script>
<link rel="modulepreload" href="{{asset('build/assets/simplebar-B35Aj-bA.js')}}" />
<script type="module" src="{{asset('build/assets/simplebar-B35Aj-bA.js')}}"></script>

<!-- PICKER JS -->
<script src="{{asset('build/assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('build/assets/libs/%40simonwep/pickr/pickr.es5.min.js')}}"></script>

<!-- AUTO COMPLETE JS -->
<script src="{{asset('build/assets/libs/%40tarekraafat/autocomplete.js/autoComplete.min.js')}}"></script>

<!-- Apex Charts JS -->
<script src="{{asset('build/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<!-- Sales Dashboard -->
<link rel="modulepreload" href="{{asset('build/assets/sales-dashboard-C66ZgfQ7.js')}}" />
<script type="module" src="{{asset('build/assets/sales-dashboard-C66ZgfQ7.js')}}"></script>


<!-- STICKY JS -->
<script src="{{asset('build/assets/sticky.js')}}"></script>

<!-- APP JS -->
<link rel="modulepreload" href="{{asset('build/assets/app-L7SVdVWK.js')}}" />
<script type="module" src="{{asset('build/assets/app-L7SVdVWK.js')}}"></script>

<!-- CUSTOM-SWITCHER JS -->
<link rel="modulepreload" href="{{asset('build/assets/custom-switcher-D6w3FEp6.js')}}" />
<script type="module" src="{{asset('build/assets/custom-switcher-D6w3FEp6.js')}}"></script>

<!-- END SCRIPTS -->

@stack('js')
</body>

</html>
