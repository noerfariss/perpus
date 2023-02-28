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
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('backend/sneat-1.0.0assets/') }}"
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

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('backend/sneat-1.0.0') }}/assets/img/favicon/favicon.ico" />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0') }}/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0') }}/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0') }}/assets/vendor/css/pages/page-auth.css" />

  </head>

  <body style="background:#181869;">
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="{{ route('login') }}" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bolder">{!! $logo_login !!}</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Selamat Datang! 👋</h4>
              <p class="mb-4">Pastikan username Anda sudah sesuai.</p>

              @if (session()->has('pesan'))
                {!! session('pesan') !!}
              @endif

              <form class="mb-3" action="{{ route('login') }}" method="POST" enctype="multipart/form-data" id="my-form">
                @csrf
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    autofocus
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                  <div id="password-error"></div>
                </div>

                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                </div>
              </form>

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <script src="{{ asset('backend/sneat-1.0.0') }}/assets/vendor/libs/jquery/jquery.js"></script>
  </body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! $validator->selector('#my-form') !!}
