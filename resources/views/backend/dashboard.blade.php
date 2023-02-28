@extends('backend.layouts.layout')

@section('konten')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                            <h5 class="card-title text-primary">Halooo <b>{{ Auth::user()->nama }}</b> Selamat datang kembali ! 🎉</h5>
                            <p class="mb-4">
                                Selamat beraktifitas & tetap semangat!
                            </p>

                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">help desk</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img
                                    src="{{ asset('backend/sneat-1.0.0/') }}/assets/img/illustrations/man-with-laptop-light.png"
                                    height="140"
                                    alt="View Badge User"
                                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div class="row">
        <!-- Order Statistics -->
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Sales</h5>
                        {{-- <small class="text-muted">42.82k Total Sales</small> --}}
                    </div>
                    <div class="dropdown">
                        <button
                        class="btn p-0"
                        type="button"
                        id="orederStatistics"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        >
                        <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                        <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                        <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div class="card-body mt-4">
                    fd
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

         <!-- Order Statistics -->
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Promo</h5>
                        {{-- <small class="text-muted">42.82k Total Sales</small> --}}
                    </div>
                    <div class="dropdown">
                        <button
                        class="btn p-0"
                        type="button"
                        id="orederStatistics"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        >
                        <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                        <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                        <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div class="card-body mt-4">
                    fd
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

        <!-- Order Statistics -->
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Visit</h5>
                        {{-- <small class="text-muted">42.82k Total Sales</small> --}}
                    </div>
                    <div class="dropdown">
                        <button
                        class="btn p-0"
                        type="button"
                        id="orederStatistics"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        >
                        <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                        <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                        <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div class="card-body mt-4">
                    fd
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

    </div>
</div>
@endsection
