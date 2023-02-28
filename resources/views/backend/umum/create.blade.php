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

                <form action="{{ route('umum.store')}}" method="POST" enctype="multipart/form-data" id="my-form">
                    @csrf

                    <div class="row mb-3">
                      <label class="col-sm-3 col-form-label" for="nama">Username</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="username">
                      </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="nama">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="email">Email</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="email">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="role">Role</label>
                        <div class="col-sm-9">
                            <select name="role[]" id="role" class="form-control role-select" data-url="{{ route('ajax-role') }}"></select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="email">Foto</label>
                        <div class="col-sm-9">
                            <div class="button-wrapper">
                                <button type="button" class="account-file-input btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                                    <span class="d-none d-sm-block">Ganti foto</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                </button>
                                <input type="hidden" name="foto" id="foto" value="">
                                <div><small class="text-muted mb-0">Format : JPG, GIF, PNG. Maksimal ukuran 2000 Kb</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="email"></label>
                        <div class="col-sm-9">
                            <div id="box-foto"></div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                      <div class="col-sm-9">
                        <a href="{{ route('user.index')}}" class="btn btn-link btn-sm">Kembali</a>
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


      <!-- Modal -->
    <div class="modal fade" id="modalUploadFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalUploadFotoLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUploadFotoLabel">Unggah Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="notif"></span>
                <form action="{{ route('ganti-foto') }}" class="dropzone" id="upload-image" method="POST" enctype="multipart/form-data">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-simpan" onclick="simpanFoto()">Tambahkan</button>
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
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const foto = response.file;
                $('.modal-body #notif').html(`<div class="alert alert-success">Foto berhasil diunggah</div>`);
                $('#foto').val(foto);
            },
            error: function(file, response){
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const pesan = response.message;
                $('.modal-body #notif').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        };

        function simpanFoto(){
            let foto = $('#foto').val();

            if(foto === '' || foto === null){
                $('#notif').html(`<div class="alert alert-danger">Tidak dapat menambahkan foto</div>`);
            }else{
                $('#modalUploadFoto').modal('hide');
                $('#box-foto').html(`<img src="{{ url('/storage/foto/thum_${foto}') }}" class="rounded">`);
            }
        }
    </script>
@endsection
