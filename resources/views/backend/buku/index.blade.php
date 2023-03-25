@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">buku
                {!! statusBtn() !!}
            </h5>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-2 mt-2"><select name="kategori" id="kategori" class="kategori-select form-control"
                            onchange="datatables.ajax.reload()" data-ajax--url="{{ route('drop-kategori') }}"></select></div>
                    <div class="col-sm-3 mt-2"><input type="text" id="cari" class="form-control"
                            placeholder="Cari..."></div>
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

                <table class="table table-sm table-hover mb-4" id="datatable">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>buku</th>
                            <th>judul</th>
                            <th>kategori</th>
                            <th>stok</th>
                            <th>dipinjam</th>
                            <th>time input</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDetailBuku" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalDetailBukuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailBukuLabel">Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td width="7">JUDUL</td>
                                    <td width="2">:</td>
                                    <td width="400"><input type="text" name="dt_judul" readonly=""
                                            class="form-no-border"></td>
                                </tr>
                                <tr>
                                    <td>ISBN</td>
                                    <td>:</td>
                                    <td><input type="text" name="dt_isbn" readonly="" class="form-no-border"></td>
                                </tr>
                                <tr>
                                    <td>KATEGORI</td>
                                    <td>:</td>
                                    <td><input type="text" name="dt_kategori" readonly="" class="form-no-border"></td>
                                </tr>
                                <tr>
                                    <td>PENGARANG</td>
                                    <td>:</td>
                                    <td><input type="text" name="dt_pengarang" readonly="" class="form-no-border">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm" id="bukutable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>kode buku</th>
                                    <th>status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .table td img {
            height: auto !important;
        }
        #bukutable_filter input{
            margin-left: 0 !important;
            display: block !important;
            width: 100% !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('.table').on('click', '.detail-anggota', function(e) {
                e.preventDefault();
                $('#modalDetailBuku').modal('show');

                const buku_id = $(this).data('id');
                const judul = $(this).data('judul');
                const pengarang = $(this).data('pengarang');
                const kategori = $(this).data('kategori');
                const isbn = $(this).data('isbn');

                $('input[name="dt_judul"]').val(judul);
                $('input[name="dt_pengarang"]').val(pengarang);
                $('input[name="dt_kategori"]').val(kategori);
                $('input[name="dt_isbn"]').val(isbn);

                var bukutable = $('#bukutable').DataTable({
                    scrollX: true,
                    processing: true,
                    serverSide: false,
                    searching: true,
                    lengthChange: false,
                    pageLength: 10,
                    bDestroy: true,
                    ajax: {
                        url: `{{ url('/auth/buku/${buku_id}') }}`,
                        type: "GET",
                    },
                    columns: [{
                            data: 'DT_RowIndex'
                        },
                        {
                            data: 'kode'
                        },
                        {
                            data: 'status'
                        },
                    ]
                });
            });
        });

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
                    data: 'buku_item_count'
                },
                {
                    data: 'dipinjam'
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
