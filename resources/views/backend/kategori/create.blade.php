@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Tambah kategori</h5>
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

                        <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data"
                            id="my-form">
                            @csrf

                            @for ($i = 0; $i < 10; $i++)
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label class="col-form-label">kategori</label>
                                        <input type="text" class="form-control" name="data[{{$i}}][kategori]">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="col-form-label">hak akses</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="data[{{$i}}][akses_siswa]" value="1" id="siswa-{{ $i }}" checked>
                                            <label class="form-check-label" for="siswa-{{ $i }}">
                                                Siswa
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="data[{{$i}}][akses_guru]"
                                                value="1" id="guru-{{ $i }}" checked>
                                            <label class="form-check-label" for="guru-{{ $i }}">
                                                Guru
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="col-form-label">urutan</label>
                                        <select name="data[{{$i}}][urutan]" id="urutan-{{ $i }}"
                                            class="select2 form-control">
                                            @for ($x = 1; $x <= 20; $x++)
                                                <option value="{{ $x }}" {{$i === $x ? 'selected' : ''}} >{{ $x }} </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            @endfor

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('kategori.index') }}" class="btn btn-link btn-sm">Kembali</a>
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
