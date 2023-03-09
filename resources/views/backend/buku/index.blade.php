@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">buku
                {!! statusBtn() !!}
            </h5>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-2 mt-2"><select name="kategori" id="kategori" class="kategori-select form-control" onchange="datatables.ajax.reload()" data-ajax--url="{{ route('drop-kategori') }}"></select></div>
                    <div class="col-sm-3 mt-2"><input type="text" id="cari" class="form-control" placeholder="Cari..."></div>
                    <div class="col-sm-7 mt-2">
                        @permission('buku-create')
                            <a href="{{ route('buku.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endpermission

                        @permission('buku-print')
                            {!! exportBtn('data', route('ajax-buku'), 'DATA BUKU') !!}
                        @endpermission
                    </div>
                </div>

                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif

                <table class="table table-sm table-hover display nowrap mb-4" id="datatable">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>buku</th>
                            <th>judul</th>
                            <th>kategori</th>
                            <th>stok</th>
                            <th>time input</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .table td img{
            height: auto !important;
        }
    </style>

    <script>
        var datatables = $('#datatable').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: false,
            pageLength: 10,
            bDestroy: true,
            ajax: {
                url: "{{ route('ajax-buku') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = $('.btn-check:checked').val();
                    d.kategori = $('#kategori').val();
                    d.cari = $('#cari').val();
                },
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'foto'
                },
                {
                    data: 'judul'
                },
                {
                    data: 'kategori'
                },
                {
                    data: 'stok'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'aksi'
                },
            ]
        });

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });
    </script>
@endsection
