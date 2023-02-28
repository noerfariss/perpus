@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Edit kelas</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form action="{{ route('kelas.update', ['kela' => $kela->id]) }}" method="POST"
                            enctype="multipart/form-data" id="my-form">
                            @csrf
                            @method('PATCH')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kode" value="{{ $kela->kode }}" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kelas</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kelas" value="{{ $kela->kelas }}">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-sm-9">
                                    <a href="{{ route('kelas.index') }}" class="btn btn-link btn-sm">Kembali</a>
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
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator->selector('#my-form') !!}
@endsection
