@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('umum.show') }}"><i class="bx bx-user me-1"></i>
                            Pengaturan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('umum.peminjaman') }}"><i class='bx bxs-key'></i>
                            Peminjaman</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Edit user</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form action="{{ route('umum.update') }}" method="POST" enctype="multipart/form-data"
                            id="my-form">
                            @csrf
                            @method('PATCH')

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Nama</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nama" value="{{ $umum->nama }}">
                                </div>
                            </div>

                            {{-- LOGO --}}
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email">Logo</label>
                                <div class="col-sm-9">
                                    <div class="button-wrapper">
                                        <button type="button" class="account-file-input btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                                            <span class="d-none d-sm-block">Ganti logo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                        </button>
                                        <input type="hidden" name="logo" id="foto" value="">
                                        <div><small class="text-muted mb-0">JPG, GIF, PNG. Maksimal ukuran 2000 Kb</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email"></label>
                                <div class="col-sm-9">
                                    <div id="box-foto">
                                        @if ($umum->logo === 'logo' || $umum->logo === null || $umum->logo == '')
                                            Logo belum diset
                                        @else
                                            <img src="{{ url('/storage/foto/thum_' . $umum->logo) }}" class="rounded">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Alamat <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="alamat" value="{{ $umum->alamat }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Provinsi <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="provinsi" id="provinsi" class="form-control provinsi-select"
                                        data-ajax--url="{{ route('drop-provinsi') }}">
                                        @if ($umum->provinsi_id)
                                            <option value="{{ $umum->provinsi->id }}">{{ $umum->provinsi->provinsi }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Kota <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="kota" id="kota" class="form-control kota-select"
                                        data-ajax--url="{{ route('drop-kota') }}">
                                        @if ($umum->kota)
                                            <option value="{{ $umum->kota->id }}">{{ $umum->kota->kota }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Kecamatan <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="kecamatan" id="kecamatan" class="form-control kecamatan-select"
                                        data-ajax--url="{{ route('drop-kecamatan') }}">
                                        @if ($umum->kecamatan)
                                            <option value="{{ $umum->kecamatan->id }}">{{ $umum->kecamatan->kecamatan }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Telpon</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="telpon" value="{{ $umum->telpon }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="email"
                                        value="{{ $umum->email }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Webisite</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="website"
                                        value="{{ $umum->website }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Timezone</label>
                                <div class="col-sm-9">
                                    <select name="timezone" id="timezone" class="form-control select2">
                                        <option value="Asia/Jakarta"
                                            {{ $umum->timezone === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta
                                        </option>
                                        <option value="Asia/Makassar"
                                            {{ $umum->timezone === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar
                                        </option>
                                        <option value="Asia/Jayapura"
                                            {{ $umum->timezone === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <a href="{{ route('umum.show') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <span class="text-danger">*) Wajib diisi</span>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal LOGO-->
    <div class="modal fade" id="modalUploadFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUploadFotoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadFotoLabel">Unggah logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif"></span>
                    <form action="{{ route('ganti-foto') }}" class="dropzone" id="upload-image" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-simpan"
                        onclick="simpanFoto()">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal FAVICON-->
    <div class="modal fade" id="modalUploadFavicon" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUploadFaviconLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadFaviconLabel">Unggah favicon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif-favicon"></span>
                    <form action="{{ route('ganti-foto') }}" class="dropzone" id="upload-favicon" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-simpan"
                        onclick="simpanFoto('favicon')">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator->selector('#my-form') !!}

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script>
        // ------------- Logo
        Dropzone.options.uploadImage = {
            maxFilesize: 2000,
            acceptedFiles: ".jpeg,.jpg,.png",
            method: 'post',
            createImageThumbnails: true,
            init: function() {
                this.on("addedfile", file => {
                    $('.btn-simpan').attr('disabled', 'disabled').text('Loading...');
                });
            },
            success: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const foto = response.file;
                $('.modal-body #notif').html(`<div class="alert alert-success">Foto berhasil diunggah</div>`);
                $('#foto').val(foto);
            },
            error: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const pesan = response.message;
                $('.modal-body #notif').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        };

        // ------------- Favicon
        Dropzone.options.uploadFavicon = {
            maxFilesize: 2000,
            acceptedFiles: ".jpeg,.jpg,.png",
            method: 'post',
            createImageThumbnails: true,
            init: function() {
                this.on("addedfile", file => {
                    $('.btn-simpan').attr('disabled', 'disabled').text('Loading...');
                });
            },
            success: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const foto = response.file;
                $('.modal-body #notif-favicon').html(
                    `<div class="alert alert-success">Favicon berhasil diunggah</div>`);
                $('#favicon').val(foto);
            },
            error: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const pesan = response.message;
                $('.modal-body #notif-favicon').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        };

        function simpanFoto($tipe = '') {
            let title = '';
            let foto = '';
            let boxImage = '';
            let notif = '';

            if ($tipe == '') {
                title = 'Logo';
                foto = $('#foto').val();
                boxImage = $('#box-foto');
                notif = $('#notif');
            } else {
                title = 'Favicon';
                foto = $('#favicon').val();
                boxImage = $('#box-favicon');
                notif = $('#notif-favicon');
            }

            console.log(boxImage);

            if (foto === '' || foto === null) {
                $(notif).html(`<div class="alert alert-danger">Tidak dapat menambahkan ${title}</div>`);
            } else {
                $('#modalUploadFoto, #modalUploadFavicon').modal('hide');
                $(boxImage).html(`<img src="{{ url('/storage/foto/thum_${foto}') }}" class="rounded">`);
            }
        }
    </script>
@endsection
