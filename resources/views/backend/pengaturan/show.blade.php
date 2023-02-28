@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
              <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Profil</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('password') }}"
                  ><i class="bx bx-bell me-1"></i> Ganti Password</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('aktivitas') }}"
                  ><i class="bx bx-link-alt me-1"></i> Aktivitas</a
                >
              </li>
            </ul>

            <div class="card mb-4">
              <h5 class="card-header">Detail Profil</h5>
              <!-- Account -->
              <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                  <img
                    src="{{ Auth::user()->foto === NULL || Auth::user()->foto == '' ? asset('backend/sneat-1.0.0//assets/img/avatars/1.png') : url('/storage/foto/thum_'.Auth::user()->foto) }}"
                    alt="user-avatar"
                    class="d-block rounded"
                    height="100"
                    width="100"
                    id="uploadedAvatar"
                  />

                  <div class="button-wrapper">
                    <button class="account-file-input btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                        <span class="d-none d-sm-block">Ganti foto</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                    </button>

                    <small class="text-muted mb-0">JPG, GIF, PNG. Maksimal ukuran 2000 Kb</smal>
                  </div>
                </div>
              </div>

              <hr class="my-0" />
              <div class="card-body">
                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif

                <form id="my-form" method="POST" enctype="multipart/form-data" action="{{ route('profil') }}">
                    @csrf
                  <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <input
                          class="form-control"
                          type="text"
                          id="username"
                          name="username"
                          value="{{ $user->username }}"
                          readonly
                        />
                      </div>

                    <div class="mb-3 col-md-6">
                      <label for="nama" class="form-label">Nama user</label>
                      <input
                        class="form-control"
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ $user->nama }}"
                        autofocus
                      />
                    </div>

                    <div class="mb-3 col-md-6">
                      <label for="email" class="form-label">E-mail</label>
                      <input
                        class="form-control"
                        type="text"
                        id="email"
                        name="email"
                        value="{{ $user->email }}"
                      />
                    </div>
                  </div>

                  <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2">Perbarui</button>
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
                <input type="hidden" name="foto" id="foto" value="">
                <button type="submit" class="btn btn-primary btn-simpan" onclick="simpanFoto()">Simpan</button>
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

        function simpanFoto(){
            const foto = $('#foto').val();
            let _token = $('input[name="_token"]').val();

            $.ajax({
                type : "POST",
                url : "{{ route('simpan-foto') }}",
                data: {
                    foto, _token
                },
                beforeSend: function(){
                    $('.btn-simpan').attr('disabled','disabled').text('Loading...');
                },
                success: function(msg){
                    $('.btn-simpan').removeAttr('disabled').text('Simpan');
                    window.location.href = "{{ route('profil') }}";
                }
            });
        }
    </script>
@endsection
