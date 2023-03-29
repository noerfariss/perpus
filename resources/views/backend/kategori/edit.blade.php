@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Edit kategori</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form action="{{ route('kategori.update', ['kategori' => $kategori->id]) }}" method="POST"
                            enctype="multipart/form-data" id="my-form">
                            @csrf
                            @method('PATCH')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kode" value="{{ $kategori->kode }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">kategori</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kategori"
                                        value="{{ $kategori->kategori }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">hak akses</label>
                                <div class="col-sm-9">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="akses_siswa" value="1"
                                            id="flexCheckDefault" {{ $kategori->akses_siswa ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Siswa
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="akses_guru" value="1"
                                            id="flexCheckChecked" {{ $kategori->akses_guru ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Guru
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">urutan</label>
                                <div class="col-sm-9">
                                    <select name="urutan" id="urutan" class="select2 form-control">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ $kategori->urutan === $i ? 'selected' : '' }} >{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-sm-9">
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
