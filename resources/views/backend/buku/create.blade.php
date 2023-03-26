@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Tambah buku</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data" id="my-form">
                            @csrf

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">judul</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="judul">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">pengarang</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pengarang">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">isbn</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                     name="isbn">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">stok</label>
                                <div class="col-sm-9">
                                    <input type="number" min="0" class="form-control" name="stok" value="0">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">kategori</label>
                                <div class="col-sm-7">
                                    <select name="kategori_id[]" id="kategori" class="form-control kategori-select" multiple
                                        data-ajax--url="{{ route('drop-kategori') }}"></select>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-sm btn-dark" onclick="openKategoriModal()" type="button"><i
                                            class='bx bx-plus-circle'></i></button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">penerbit</label>
                                <div class="col-sm-7">
                                    <select name="penerbit_id" id="penerbit_id" class="form-control penerbit-select"
                                        data-ajax--url="{{ route('drop-penerbit') }}"></select>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-sm btn-dark" onclick="openPenerbitModal()" type="button"><i
                                            class='bx bx-plus-circle'></i></button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email">buku pdf</label>
                                <div class="col-sm-9">
                                    <div class="button-wrapper">
                                        <button type="button" class="account-file-input btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#modalUploadPDF">
                                            <span class="d-none d-sm-block">Ganti Berkas</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                        </button>
                                        <input type="hidden" name="pdf" id="pdf" value="">
                                        <div><small class="text-muted mb-0">Format : PDF Maksimal ukuran 5000
                                                Kb</small></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email"></label>
                                <div class="col-sm-9">
                                    <div id="box-pdf"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email">Foto</label>
                                <div class="col-sm-9">
                                    <div class="button-wrapper">
                                        <button type="button" class="account-file-input btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                                            <span class="d-none d-sm-block">Ganti foto</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                        </button>
                                        <input type="hidden" name="foto" id="foto" value="">
                                        <div><small class="text-muted mb-0">Format : JPG, GIF, PNG. Maksimal ukuran 2000
                                                Kb</small></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email"></label>
                                <div class="col-sm-9">
                                    <div id="box-foto"></div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('buku.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>

            </div>
        </div>
    </div>


    <!-- UPLOAD FOTO -->
    <div class="modal fade" id="modalUploadFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUploadFotoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadFotoLabel">Unggah Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif"></span>
                    <form action="{{ route('ganti-foto-buku') }}" class="dropzone" id="upload-image" method="POST"
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

    <!-- UPLOAD PDF -->
    <div class="modal fade" id="modalUploadPDF" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUploadPDFLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadPDFLabel">Unggah Berkas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif-pdf"></span>
                    <form action="{{ route('ganti-pdf-buku') }}" class="dropzone" id="upload-pdf" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-simpan"
                        onclick="simpanPDF()">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PENERBIT -->
    <div class="modal fade" id="modalPenerbit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalPenerbitLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('penerbit.store') }}" method="POST" enctype="multipart/form-data"
                    id="PenerbitForm">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPenerbitLabel">Penerbit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="notif_penerbit"></span>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">kode</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kode" id="kode_penerbit" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">penerbit</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="penerbit">
                                <input type="hidden" class="form-control" name="tipe" value="ajax">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm btn-simpan">Tambahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- KATEGORI -->
    <div class="modal fade" id="modalKategori" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalKategoriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data"
                    id="KategoriForm">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKategoriLabel">Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="notif_kategori"></span>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">kode</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kode" id="kode_kategori" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">kategori</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kategori">
                                <input type="hidden" class="form-control" name="tipe" value="ajax">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm btn-simpan">Tambahkan</button>
                    </div>
                </form>
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

        Dropzone.options.uploadPdf = {
            maxFilesize: 5000,
            acceptedFiles: ".pdf",
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
                $('.modal-body #notif-pdf').html(`<div class="alert alert-success">Berkas berhasil diunggah</div>`);
                $('#pdf').val(foto);
            },
            error: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const pesan = response.message;
                $('.modal-body #notif-pdf').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        };

        function simpanFoto() {
            let foto = $('#foto').val();

            if (foto === '' || foto === null) {
                $('#notif').html(`<div class="alert alert-danger">Tidak dapat menambahkan foto</div>`);
            } else {
                $('#modalUploadFoto').modal('hide');
                $('#box-foto').html(`<img src="{{ url('/storage/buku/thum_${foto}') }}" class="rounded">`);
            }
        }

        function simpanPDF() {
            let pdf = $('#pdf').val();

            if (pdf === '' || pdf === null) {
                $('#notif-pdf').html(`<div class="alert alert-danger">Tidak dapat menambahkan berkas</div>`);
            } else {
                $('#modalUploadPDF').modal('hide');
                $('#box-pdf').html(`<img src="{{ url('/storage/buku/pdf/demo/pdf-icon.png') }}" width="60">`);
            }
        }

        function openKategoriModal(){
            $('#kode_kategori').val('');
            $('#modalKategori').modal('show');

            $.ajax({
                type : 'GET',
                url : '{{ route("buku.get_kode_kategori") }}',
            })
            .done(function(res){
                const kode = res.data;
                $('#kode_kategori').val(kode);
            });
        }

        function openPenerbitModal(){
            $('#kode_penerbit').val('');
            $('#modalPenerbit').modal('show');

            $.ajax({
                type : 'GET',
                url : '{{ route("buku.get_kode_penerbit") }}',
            })
            .done(function(res){
                const kode = res.data;
                $('#kode_penerbit').val(kode);
            });
        }

        $(document).ready(function() {
            // --- PENERBIT FORM
            $('#PenerbitForm').submit(function(e) {
                e.preventDefault();

                let notif_penerbit = $('#notif_penerbit');

                const url = $(this).attr('action');
                const data = $(this).serialize();

                $.ajax({
                        type: 'POST',
                        url: url,
                        data: data,
                    })
                    .done(function(e) {
                        const msg = e.message;
                        $(notif_penerbit).html(`<div class="alert alert-success">${msg}</div>`);

                        setTimeout(() => {
                            $('#modalPenerbit').modal('hide');
                            $(notif_penerbit).html('');
                            $('input[name="kode"]').val('');
                            $('input[name="penerbit"]').val('');
                        }, 1500);
                    })
                    .fail(function(err) {
                        const errors = err.responseJSON.errors;
                        let show_notif = '';

                        $.each(errors, function(i, val) {
                            $.each(val, function(x, y) {
                                show_notif +=
                                    `<div class="alert alert-danger">${y}</div>`;
                            });
                        });

                        $(notif_penerbit).html(show_notif);
                    });
                return false;
            });

            // --- KATEGORI FORM
            $('#KategoriForm').submit(function(e) {
                e.preventDefault();

                let notif_kategori = $('#notif_kategori');

                const url = $(this).attr('action');
                const data = $(this).serialize();

                $.ajax({
                        type: 'POST',
                        url: url,
                        data: data,
                    })
                    .done(function(e) {
                        const msg = e.message;
                        $(notif_kategori).html(`<div class="alert alert-success">${msg}</div>`);

                        setTimeout(() => {
                            $('#modalKategori').modal('hide');
                            $(notif_kategori).html('');
                            $('input[name="kategori"]').val('');
                        }, 1500);
                    })
                    .fail(function(err) {
                        const errors = err.responseJSON.errors;
                        let show_notif = '';

                        $.each(errors, function(i, val) {
                            $.each(val, function(x, y) {
                                show_notif +=
                                    `<div class="alert alert-danger">${y}</div>`;
                            });
                        });

                        $(notif_kategori).html(show_notif);
                    });
                return false;
            });
        });
    </script>
@endsection
