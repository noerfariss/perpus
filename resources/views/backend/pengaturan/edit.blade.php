@extends('layouts.layout')

@section('konten')
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="row row-sm mg-b-20">
                    <div class="col-lg-8 ht-lg-100p offset-lg-2">
                    <div class="card card-table-one">
                        <div>
                            <h6 class="card-title">Pembaruan Profil</h6>
                            <p class="card-text">Pastikan profil perusahaan Anda sudah sesuai.</p>
                        </div>

                        <form action="{{ url('/perusahaan', ['perusahaan' => $perusahaan->id]) }}" method="POST" id="my-form">
                        @csrf
                        @method('PATCH')

                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <div class="form-group row mt-4">
                            <label for="" class="col-sm-3">Nama Perusahaan</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_perusahaan" class="form-control" value="{{ $perusahaan->nama_perusahaan }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">Email</label>
                            <div class="col-sm-9">
                                <input type="text" name="email" class="form-control" value="{{ $perusahaan->email }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">Jumlah Pegawai</label>
                            <div class="col-sm-9">
                                <input type="text" name="jumlah_pegawai" class="form-control" value="{{ $perusahaan->jumlah_pegawai }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">Penanggungjawab</label>
                            <div class="col-sm-9">
                                <input type="text" name="penanggung_jawab" class="form-control" value="{{ $perusahaan->penanggung_jawab }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">No. Kantor</label>
                            <div class="col-sm-9">
                                <input type="text" name="no_kantor" class="form-control" value="{{ $perusahaan->no_kantor }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">No. Handphone</label>
                            <div class="col-sm-9">
                                <input type="text" name="no_hp" class="form-control" value="{{ $perusahaan->no_hp }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">Whatsapp</label>
                            <div class="col-sm-9">
                                <input type="text" name="wa" class="form-control" value="{{ $perusahaan->wa }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">Alamat</label>
                            <div class="col-sm-9">
                                <input type="text" name="alamat" class="form-control" value="{{ $perusahaan->alamat }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3">Foto</label>
                            <div class="col-sm-9">
                                <div class="btn-group">
                                    {{-- <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#gantiFotoModal">Ganti Foto</button> --}}
                                    <button type="button" class="btn btn-outline-secondary" onclick="openModalFoto()">Ganti Foto</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="{{ route('perusahaan.show', ['perusahaan' => $perusahaan->id]) }}" class="btn btn-link">Kembali</a>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                        </form>
                    </div><!-- card -->
                    </div><!-- col -->

                </div><!-- row -->
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalFoto" tabindex="-1" role="dialog" aria-labelledby="modalFotoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="modalFotoLabel">Unggah foto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <span id="notif"></span>
                <form action="{{ url('/ganti-foto') }}" class="dropzone" id="upload-image" method="POST" enctype="multipart/form-data">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="foto" value="">
                <button type="button" class="btn btn-primary btn-simpan" onclick="simpanFoto()">Simpan</button>
            </div>
        </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#my-form') !!}

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script>
        function openModalFoto(){
            $('.modal-body #notif').html('');
            $('.modal-footer #foto').val('');
            $('#modalFoto').modal('show');
        }

        function simpanFoto(){
            const foto = $('#foto').val();
            let _token = $('input[name="_token"]').val();

            $.ajax({
                type : "POST",
                url : "{{ url('/update-foto') }}",
                data: {
                    foto, _token
                },
                beforeSend: function(){
                    $('.btn-simpan').attr('disabled','disabled').text('Loading...');
                },
                success: function(msg){
                    $('.btn-simpan').removeAttr('disabled').text('Simpan');
                    window.location.href = "{{ route('perusahaan.edit', ['perusahaan' => $perusahaan->id]) }}";
                }
            });
        }
    </script>

    <script>
        Dropzone.options.uploadImage = {
            maxFilesize : 2000,
            acceptedFiles: ".jpeg,.jpg,.png",
            method : 'post',
            createImageThumbnails: true,
            init: function(){
                this.on("addedfile", file => {
                    $('.btn-simpan').attr('disabled','disabled').text('Loading...');
                });
            },
            success: function (file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Simpan');
                const foto = response.file;
                $('.modal-body #notif').html(`<div class="alert alert-success">Foto berhasil diunggah</div>`);
                $('.modal-footer #foto').val(foto);
            },
            error: function(file, response){
                $('.btn-simpan').removeAttr('disabled').text('Simpan');
                const pesan = response.message;
                $('.modal-body #notif').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        };
    </script>

@endsection
