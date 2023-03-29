@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">kategori
                {!! statusBtn() !!}
            </h5>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-3 mt-2"><input type="text" id="cari" class="form-control" placeholder="Cari...">
                    </div>
                    <div class="col-sm-9 mt-2">
                        @permission('kategori-create')
                            <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endpermission

                        @permission('kategori-print')
                            {!! exportBtn('data', route('ajax-kategori'), 'DATA KATEGORI') !!}
                        @endpermission
                    </div>
                </div>

                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif

                <table class="table table-sm table-hover display nowrap mb-4" id="datatable">
                    <thead>
                        <tr>
                            <th>kode</th>
                            <th>kategori</th>
                            <th>buku</th>
                            <th>akses siswa</th>
                            <th>akses guru</th>
                            <th>urutan</th>
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
                url: "{{ route('ajax-kategori') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = $('.btn-check:checked').val();
                    d.cari = $('#cari').val();
                },
            },
            columns: [
                {
                    data: 'kode'
                },
                {
                    data: 'kategori'
                },
                {
                    data: 'buku_count'
                },
                {
                    data: 'akses_siswa'
                },
                {
                    data: 'akses_guru'
                },
                {
                    data: 'urutan'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'aksi'
                },
            ]
        });

        // datatables.on('order.dt search.dt', function() {
        //     let i = 1;

        //     datatables.cells(null, 0, {
        //         search: 'applied',
        //         order: 'applied'
        //     }).every(function(cell) {
        //         this.data(i++);
        //     });
        // }).draw();

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });
    </script>
@endsection
