@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Pengaturan</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username" value="{{ $pengaturan->nama }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Logo</label>
                            <div class="col-sm-9">
                                @if ($pengaturan->logo === 'logo' || $pengaturan->logo === null || $pengaturan->logo == '')
                                    Logo belum diset
                                @else
                                    <img src="{{ url('/storage/foto/thum_' . $pengaturan->logo) }}" alt="">
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Favicon</label>
                            <div class="col-sm-9">
                                @if ($pengaturan->logo === 'logo' || $pengaturan->logo === null || $pengaturan->logo == '')
                                    Favicon belum diset
                                @else
                                    <img src="{{ url('/storage/foto/' . $pengaturan->favicon) }}" alt="">
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Alamat</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->alamat }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Provinsi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->provinsi?->provinsi }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Kota</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->kota?->kota }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Kecamatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->kecamatan?->kecamatan }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Telpon</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->telpon }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->email }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Website</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->website }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Timezone</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->timezone }}" disabled>
                            </div>
                        </div>

                        @permission('umum-update')
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <a href="{{ route('umum.edit') }}" class="btn btn-primary btn-sm">edit</a>
                                </div>
                            </div>
                        @endpermission

                    </div>
                    <!-- /Account -->
                </div>

            </div>
        </div>
    </div>
@endsection
