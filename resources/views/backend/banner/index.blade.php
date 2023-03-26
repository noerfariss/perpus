@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">banner
                {!! statusBtn() !!}
            </h5>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-2 mt-2"><select name="kelas" id="kelas" class="form-control kelas-select"
                            data-ajax--url={{ route('drop-kelas') }} onchange="datatables.ajax.reload()"></select>
                    </div>
                    <div class="col-sm-3 mt-2"><input type="text" id="cari" class="form-control"
                            placeholder="Cari...">
                    </div>
                    <div class="col-sm-7 mt-2">
                        @permission('banner-create')
                            <a href="{{ route('banner.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endpermission

                        @permission('banner-print')
                            {!! exportBtn(['data', 'foto'], route('ajax-banner'), 'DATA banner') !!}
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
                            <th>banner</th>
                            <th>keterangan</th>
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
                url: "{{ route('ajax-banner') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = $('.btn-check:checked').val();
                    d.cari = $('#cari').val();
                    d.kelas = $('#kelas').val();
                },
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'foto'
                },
                {
                    data: 'keterangan'
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
