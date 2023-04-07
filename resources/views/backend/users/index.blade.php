@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">User
                {!! statusBtn() !!}
            </h5>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-3 mt-2"><input type="text" id="cari" class="form-control" placeholder="Cari...">
                    </div>
                    <div class="col-sm-9 mt-2">
                        @permission('user-create')
                            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endpermission

                        @permission('user-print')
                            {!! exportBtn(['data','foto'], route('ajax-user'), 'USER') !!}
                        @endpermission
                    </div>
                </div>

                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif

                <table class="table table-sm table-hover display nowrap mb-4" id="datatable">
                    <thead>
                        <tr>
                            <th>foto</th>
                            <th>nama</th>
                            <th>username</th>
                            <th>email</th>
                            <th>role</th>
                            <th>time input</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalGantiPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalGantiPasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ route('ganti-password') }}" method="POST" enctype="multipart/form-data"
                    id="form-ganti-password">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalGantiPasswordLabel">Ganti Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="notif"></span>

                        @csrf
                        <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" for="nama">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control username" disabled>
                                <input type="hidden" class="username" name="username">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" for="nama">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama" disabled>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" for="email">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="email" disabled>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <input type="hidden" id="id" name="id">
                            <label class="col-sm-3 col-form-label" for="password">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" for="password_confirmation">Konfirmasi Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

    <script>
        let datatables = $('#datatable').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: false,
            pageLength: 10,
            bDestroy: true,
            ajax: {
                url: "{{ route('ajax-user') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.cari = $('#cari').val();
                    d.status = $('.btn-check:checked').val();
                },
            },
            columns: [
                {
                    data: 'foto'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role'
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

        $('.table').on('click', '.btn-open-ganti-password', function(e) {
            e.preventDefault();
            let username = $(this).data('username');
            let nama = $(this).data('nama');
            let email = $(this).data('email');
            let id = $(this).data('id');

            $('#modalGantiPassword').modal('show');
            $('#notif').html('');
            $('#password, #password_confirmation').val('');

            $('#id').val(id);
            $('.username').val(username);
            $('#nama').val(nama);
            $('#email').val(email);
        });

        $('#form-ganti-password').submit(function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            let url = $(this).attr('action');

            $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                })
                .done(function(e) {
                    $('#notif').html(`<div class="alert alert-success">Password berhasil dirubah</div>`);
                    setTimeout(() => {
                        $('#modalGantiPassword').modal('hide');
                    }, 1500);
                })
                .fail(function(e) {
                    let response = e.responseJSON;
                    let errors = response.errors.password;

                    let notif = '<ul>';
                    $.each(errors, function(i, val) {
                        notif += `<li>${val}</li>`;
                    });
                    notif += '</ul>';

                    $('#notif').html('');
                    $('#notif').html(`<div class="alert alert-danger">${notif}</div>`);
                });
        });
    </script>
@endsection
