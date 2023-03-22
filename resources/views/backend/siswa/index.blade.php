@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">siswa
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
                        @permission('siswa-create')
                            <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endpermission

                        @permission('siswa-print')
                            {!! exportBtn(['data', 'foto'], route('ajax-siswa'), 'DATA siswa') !!}
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
                            <th>foto</th>
                            <th>siswa</th>
                            <th>ttl</th>
                            <th>kelas</th>
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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('siswa-ganti-password') }}" method="POST" enctype="multipart/form-data"
                    id="form-ganti-password">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalGantiPasswordLabel">Ganti Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="notif"></span>

                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="nama">nomor Anggota</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control nomor_anggota" name="nomor_anggota" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="nama">Nomor Induk</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control nomor_induk" name="nomor_induk" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="nama">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control nama" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="kelas">Kelas</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control kelas" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <input type="hidden" id="id" name="id">
                            <label class="col-sm-4 col-form-label" for="password">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="password_confirmation">Konfirmasi Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm btn-simpan">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDetailAnggota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalDetailAnggotaLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailAnggotaLabel">siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif"></span>

                    @csrf
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label" for="nama">nomor Anggota</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control dt_nomor_anggota" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label" for="nama">Nomor Induk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control dt_nomor_induk" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label" for="nama">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control dt_nama" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label" for="kelas">Kelas</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control dt_kelas" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label" for="kelas">TTL</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control dt_ttl" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label" for="kelas">Jenis Kelamin</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control dt_jenis_kelamin" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-4 col-form-label" for="kelas">Alamat</label>
                        <div class="col-sm-8">
                            <textarea class="dt_alamat form-control" rows="4" disabled></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="" target="_blank" class="btn btn-primary btn-sm btn-kartu">Download kartu</a>
                </div>
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
                url: "{{ route('ajax-siswa') }}",
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
                    data: 'anggota'
                },
                {
                    data: 'ttl'
                },
                {
                    data: 'kelas.kelas'
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
            let anggota = $(this).data('anggota');
            let induk = $(this).data('induk');
            let nama = $(this).data('nama');
            let kelas = $(this).data('kelas');
            let id = $(this).data('id');

            $('#modalGantiPassword').modal('show');
            $('#notif').html('');
            $('#password, #password_confirmation').val('');

            $('#id').val(id);
            $('.nomor_anggota').val(anggota);
            $('.nomor_induk').val(induk);
            $('.nama').val(nama);
            $('.kelas').val(kelas);
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

        $('.table').on('click', '.btn-anggota', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let anggota = $(this).data('anggota');
            let induk = $(this).data('induk');
            let nama = $(this).data('nama');
            let kelas = $(this).data('kelas');
            let jenis_kelamin = $(this).data('jenis_kelamin')
            let foto = $(this).data('foto');
            let ttl = $(this).data('ttl');
            let alamat = $(this).data('alamat');

            $('#modalDetailAnggota').modal('show');

            const url = '{{ url("/auth/siswa/kartu") }}';
            $('.btn-kartu').attr('href',`${url}/${anggota}`);
            $('.dt_nomor_anggota').val(anggota);
            $('.dt_nomor_induk').val(induk);
            $('.dt_nama').val(nama);
            $('.dt_kelas').val(kelas);
            $('.dt_ttl').val(ttl);
            $('.dt_jenis_kelamin').val(jenis_kelamin);
            $('.dt_alamat').text(alamat);
        });
    </script>
@endsection
