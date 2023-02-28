<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('backend/sneat-1.0.0/') }}/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>{{ $title_web }}</title>

    <meta name="description" content="" />
    {{-- <link rel="shortcut icon"  href="{{ $favicon }}" /> --}}

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('backend.layouts.sidebar')

            <!-- Layout container -->
            <div class="layout-page">
                @include('backend.layouts.header')

                <div class="content-wrapper">

                    @yield('konten')

                    @include('backend.layouts.footer')

                    <div class="content-backdrop fade"></div>
                </div>

            </div>

      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    @csrf

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/js/helpers.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/js/menu.js"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/js/main.js"></script>

    @yield('script', '')

  </body>
</html>
