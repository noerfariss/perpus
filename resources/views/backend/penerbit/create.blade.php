@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Tambah penerbit</h5>
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

                        <form action="{{ route('penerbit.store') }}" method="POST" enctype="multipart/form-data"
                            id="my-form">
                            @csrf

                            @for ($i = 1; $i <= 10; $i++)
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">penerbit</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="penerbit[]">
                                    </div>
                                </div>
                            @endfor

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('penerbit.index') }}" class="btn btn-link btn-sm">Kembali</a>
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
