@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('umum.show') }}"><i class="bx bxs-cog me-1"></i> Pengaturan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('umum.peminjaman') }}"><i class='bx bx-up-arrow-alt'></i>
                            Peminjaman</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Pengaturan</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form action="{{ route('umum.peminjaman.edit', ['peminjaman' => $pengaturan->id]) }}" method="POST" id="my-form">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Durasi pinjam</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="batas_pengembalian"
                                        value="{{ $pengaturan->batas_pengembalian }}">
                                    <small>Durasi peminjaman buku dihitung dalam satuan hari </small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Batas peminjaman</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $pengaturan->batas_peminjaman }}" name="batas_peminjaman">
                                    <small>Batas jumlah buku dalam sekali pinjam. 0 tidak terbatas</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Denda</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $pengaturan->denda }}" name="denda">
                                    <small>Denda yang diberlakukan dalam per hari jika terlambat mengembalikan buku dari
                                        batas pengembalian yang ditentukan</small>
                                </div>
                            </div>

                            @permission('umum-update')
                                <div class="row justify-content-end">
                                    <div class="col-sm-9">
                                        <a href="{{ route('umum.peminjaman') }}" class="btn btn-link btn-sm">Kembali</a>
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    </div>
                                </div>
                            @endpermission
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
