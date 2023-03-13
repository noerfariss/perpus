@extends('backend.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-sm-4">
                <div class="card mb-5">
                    <h5 class="card-header">Peminjaman</h5>

                    <div class="card-body" id="pencarian">
                        <form action="{{ route('get-anggota') }}" method="POST" id="cari-anggota">
                            @csrf
                            <input type="text" id="cari" class="form-control" name="anggota"
                                placeholder="Nomor Induk atau Nomor Anggota">
                        </form>

                        <div id="notif"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card">
                    <h5 class="card-header">Anggota</h5>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <td width="115">NO. ANGGOTA</td>
                                                <td width="2">:</td>
                                                <td width="250"><input type="text" name="nomor_anggota" readonly
                                                        class="form-no-border"></td>
                                                <td width="2"></td>
                                                <td width="115">NO. INDUK</td>
                                                <td width="2">:</td>
                                                <td><input type="text" name="nomor_induk" readonly
                                                        class="form-no-border"></td>
                                            </tr>
                                            <tr>
                                                <td>NAMA</td>
                                                <td>:</td>
                                                <td><input type="text" name="nama" readonly class="form-no-border">
                                                </td>
                                                <td></td>
                                                <td>JENIS KELAMIN</td>
                                                <td>:</td>
                                                <td><input type="text" name="jenis_kelamin" readonly
                                                        class="form-no-border"></td>
                                            </tr>
                                            <tr>
                                                <td>KELAS</td>
                                                <td>:</td>
                                                <td><input type="text" name="kelas" readonly class="form-no-border">
                                                </td>
                                                <td></td>
                                                <td>KEANGGOTAAN</td>
                                                <td>:</td>
                                                <td><input type="text" name="jabatan" readonly class="form-no-border">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 mt-4">
                <div class="card">
                    <h5 class="card-header">Buku</h5>

                    <div class="card-body">
                        <form action="{{ route('cari-buku') }}" method="POST" id="cari-buku">
                            @csrf
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="text" id="buku" name="buku" class="form-control"
                                        placeholder="Cari atau scan kode buku">
                                </div>
                                <div class="col-sm-8 box-btn-proses-pinjam"></div>
                            </div>

                            <div class="row">

                                <div class="col-sm-8">
                                    <table class="table table-sm mt-4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>kode buku</th>
                                                <th>judul</th>
                                                <th>pengarang</th>
                                                <th>isbn</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="box-item-buku"></tbody>
                                    </table>
                                </div>
                                <div class="col-sm-4">
                                    <div class="box-transaksi">
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td width="110">No. Transaksi</td>
                                                    <td width="2">:</td>
                                                    <td><span id="box-no-transaksi"></span></td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah</td>
                                                    <td>:</td>
                                                    <td><span id="box-jumlah-transaksi">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal</td>
                                                    <td>:</td>
                                                    <td><span id="box-tanggal-transaksi">{{ $tanggal_sekarang }}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        #pencarian {
            height: 55px !important;
        }

        #pencarian .row {
            margin: 0 !important;
        }

        #cari {
            width: 90%;
            margin: 30px auto 0 auto;
            padding: 12px;
            border: 2px solid #74747b;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
        }

        table tbody tr td {
            font-size: .8rem !important;
        }

        .box-transaksi {
            margin: 22px 0 0 0;
            padding: 20px;
            border: 2px dashed #999;
            border-radius: 4px;
        }
    </style>

    <script>
        let cari_anggota = $('input[name="anggota"]');
        let cari_buku = $('input[name="buku"]');

        let nomor_anggota = $('input[name="nomor_anggota"]');
        let nomor_induk = $('input[name="nomor_induk"]');
        let nama = $('input[name="nama"]');
        let jenis_kelamin = $('input[name="jenis_kelamin"]');
        let kelas = $('input[name="kelas"]');
        let jabatan = $('input[name="jabatan"]');

        let btn_proses_pinjam =
            `<button type="button" class="btn btn-primary float-end btn-proses-pinjam"><i class='bx bx-check' style="font-size:22px; float:left;"></i> Simpan</button>`;
        let box_btn_proses_pinjam = $('.box-btn-proses-pinjam');

        resetFormAnggota();

        $(document).ready(function() {
            $('#cari-anggota').submit(function(e) {
                e.preventDefault();

                const url = $(this).attr('action');
                const data = $(this).serialize();
                const notif = $('#notif');

                $.ajax({
                        type: 'POST',
                        url: url,
                        data: data,
                    })
                    .done(function(msg) {
                        const data = msg.data;
                        const status = msg.status;

                        if (status === true) {
                            $(notif).hide();
                            $(nomor_anggota).val(data.nomor_anggota);
                            $(nomor_induk).val(data.nomor_induk);
                            $(nama).val(data.nama);
                            $(jenis_kelamin).val(data.jenis_kelamin === 'P' ? 'Perempuan' :
                                'Laki-laki');
                            $(kelas).val(data.kelas === null ? '-' : data.kelas.kelas);
                            $(jabatan).val(data.jabatan);

                        } else {
                            notifAlert('alert', msg.message);
                            resetFormAnggota();
                        }
                    })
                    .fail(function(err) {
                        const errors = err.responseJSON.errors;

                        let box_notif = '';
                        $.each(errors, function(i, val) {
                            $.each(val, function(x, y) {
                                box_notif += `${y}`;
                            });
                        });

                        notifAlert('alert', box_notif);
                        resetFormAnggota();
                    });

                return false;
            });

            $('#cari-buku').submit(function(e) {
                e.preventDefault();

                const box_item_buku = $('#box-item-buku');
                const url = $(this).attr('action');
                const data = $(this).serialize();

                const buku = $('#buku').val();
                const cek = $('.cek_kode').text();

                let kode_buku_arr = cekJumlahBuku();

                if (jQuery.inArray(buku, kode_buku_arr) !== -1) {
                    notifAlert('alert', 'Kode buku sudah ada di daftar');
                    return false;
                }

                $.ajax({
                        type: 'POST',
                        url: url,
                        data: data,
                    })
                    .done(function(msg) {
                        const data = msg.data;
                        const status = msg.status;

                        if (status === true) {
                            $('#cari-buku #buku').val('').focus();
                            $(box_item_buku).append(`<tr>
                                                        <td class="no_urut"></td>
                                                        <td class="cek_kode">${data.kode}</td>
                                                        <td>${data.buku.judul}</td>
                                                        <td>${data.buku.pengarang}</td>
                                                        <td>${data.buku.isbn}</td>
                                                        <td><button type="button" class="btn btn-sm btn-dark btn-remove-item"><i class='bx bx-trash-alt' ></i></button></td>
                                                    </tr>`);
                            noUrut();
                            cekJumlahBuku();
                        } else {
                            notifAlert('alert', msg.message);
                        }
                    })
                    .fail(function(err) {
                        const errors = err.responseJSON.errors;

                        let box_notif = '';
                        $.each(errors, function(i, val) {
                            $.each(val, function(x, y) {
                                box_notif += `${y}`;
                            });
                        });

                        notifAlert('alert', box_notif);
                    });

                return false;
            });

            $('#box-item-buku').on('click', '.btn-remove-item', function(e) {
                e.preventDefault();
                $(this).parent('td').parent('tr').remove();
                noUrut();
                cekJumlahBuku();
            });

            $('.box-btn-proses-pinjam').on('click', '.btn-proses-pinjam', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda sudah yakin?',
                    text: "Pastikan data yang akan diinputkan sudah benar",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1A237E',
                    cancelButtonColor: '#B71C1C',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const anggota = $('input[name="nomor_anggota"]').val();
                        const kode_buku = $('.cek_kode');
                        let kode_buku_arr = [];
                        $.each(kode_buku, function(i, val) {
                            kode_buku_arr.push($(this).text());
                        });

                        const kode_transaksi = $('#box-no-transaksi').text();

                        $.ajax({
                                type: 'POST',
                                url: '{{ route('simpan-peminjaman') }}',
                                data: {
                                    _token: $('input[name="_token"]').val(),
                                    anggota,
                                    kode_buku_arr,
                                    kode_transaksi
                                }
                            })
                            .done(function(msg) {
                                notifAlert('success', msg.message);
                                resetFormAnggota();
                            })
                            .fail(function(err) {
                                const errors = err.responseJSON.errors;

                                let box_notif = '';
                                $.each(errors, function(i, val) {
                                    $.each(val, function(x, y) {
                                        box_notif += `${y}`;
                                    });
                                });

                                notifAlert('alert', box_notif);
                            });
                        return false;
                    }
                });
            });
        });

        function cekJumlahBuku() {
            let kode_buku_arr = [];
            $('.cek_kode').each(function() {
                let kode_buku = $(this).text();
                kode_buku_arr.push(kode_buku);
            });

            let jumlah = kode_buku_arr.length;
            $('#box-jumlah-transaksi').text(jumlah);

            if (jumlah > 0) {
                $(box_btn_proses_pinjam).html(btn_proses_pinjam);
            } else {
                $(box_btn_proses_pinjam).html('');
            }

            return kode_buku_arr;
        }

        function noUrut() {
            let no_urut_arr = [];
            let x = 1;
            $('.no_urut').each(function(i, val) {
                $('.no_urut').eq(i).text(x++);
            });
        }

        function notifAlert(tipe = 'alert', pesan = '', url) {
            if (tipe === 'alert') {
                Swal.fire({
                    text: pesan,
                    icon: 'warning',
                    showCancelButton: false,
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 4500,
                });

            } else if (tipe === 'success') {
                Swal.fire({
                    text: pesan,
                    icon: 'success',
                    showCancelButton: false,
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 4500,
                });

            }
        }

        function resetFormAnggota() {
            $(cari_anggota).val('');
            $(cari_buku).val('');
            $('#box-item-buku').html('');
            cekJumlahBuku();
            getNomorTransaksi();

            $(nomor_anggota).val('');
            $(nomor_induk).val('');
            $(nama).val('');
            $(jenis_kelamin).val('');
            $(kelas).val('');
            $(jabatan).val('');
        }

        function getNomorTransaksi() {
            $.ajax({
                    type: 'GET',
                    url: '{{ route('get-no-transaksi') }}',
                })
                .done(function(res) {
                    $('#box-no-transaksi').text(res);
                });
        }
    </script>
@endsection
