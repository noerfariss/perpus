@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-4">
              <h5 class="card-header">Tambah Permission</h5>
              <div class="card-body">
                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif

                <form action="{{ route('permission.store')}}" method="POST" enctype="multipart/form-data" id="my-form">
                    @csrf

                    <div class="row mb-3">
                      <label class="col-sm-3 col-form-label" for="name">Permission</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="name">
                      </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="description">Deskripsi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="description">
                        </div>
                    </div>


                    <div class="row justify-content-end">
                      <div class="col-sm-9">
                        <a href="{{ route('permission.index')}}" class="btn btn-link btn-sm">Kembali</a>
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

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#my-form') !!}

@endsection
