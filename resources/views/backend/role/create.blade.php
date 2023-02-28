@extends('backend.layouts.layout')

@section('konten')
<form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data" id="my-form">
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-5">
                <div class="card mb-4">
                    <h5 class="card-header">Tambah Role</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="name">role</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="name">label</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="display_name">
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
                                    <a href="{{ route('role.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                    </div>
                    <!-- /Account -->
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card mb-4">
                    <h5 class="card-header">Permission</h5>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <input type="text" id="cari" class="form-control" placeholder="Cari...">
                            </div>
                        </div>
                        <table class="table table-sm display nowrap" id="datatable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>permission</th>
                                    <th>description</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator->selector('#my-form') !!}

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

    <script>
        var datatables = $('#datatable').DataTable({
            scrollX: true,
            scrollY: "200px",
            scrollCollapse: true,
            processing: true,
            serverSide: false,
            searching: true,
            lengthChange: false,
            paging: false,
            bDestroy: true,
            sort: false,
            ajax: {
                url: "{{ route('ajax-permission') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.is_role = true;
                },
            },
            columns: [{
                    data: 'checkbox'
                },
                {
                    data: 'name'
                },
                {
                    data: 'description'
                },
            ],
        });

        $('#cari').keyup(function(){
            datatables.search($(this).val()).draw();
        });
    </script>
@endsection
