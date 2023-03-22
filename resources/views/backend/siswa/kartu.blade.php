<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>kartu</title>
    <style>
        .page-break {
            page-break-after: always;
        }

        body {
            background: url({{ public_path('/storage/foto/bg-kartu.jpg') }}) no-repeat;
            background-size:cover;
        }

        @page {
            margin: 0;
            font-size: 11px;
            background-image: url({{ public_path('/storage/foto/bg-kartu.jpg') }})
        }
        .wrapper{
            margin: 3mm;
        }
        header {
            margin: 0 auto;
            display: block;
            padding-bottom: 3px;
        }

        #logo-kiri {
            width: 50px;
            height: 50px;
        }

        #logo-kanan {
            width: 50px;
            height: 50px;
            display: inline-block;
        }

        header h2 {
            margin: 0 auto;
            text-align: center;
            text-transform: uppercase;
            padding: 0;
            font-size: 11px;
        }

        header h1 {
            margin: 0 auto;
            text-align: center;
            text-transform: uppercase;
            padding: 0;
            font-size: 13px;
        }

        header p {
            margin: 3px auto;
            text-align: center;
            padding: 0;
            font-size: 8px;
        }

        #body h1 {
            padding: 0;
            margin: 0;
            font-size: 12px;
        }

        table {
            margin-top: 5px;
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            font-size: 10px;
        }

        tr td {
            padding: 0;
        }

        #foto {
            width: 2cm;
            height: 2.5cm;
            border: 1px solid;
            border-radius: 8px;
            overflow: hidden;
            position: absolute;
            right: 0;
            background: #999;
            margin-top: -40px;
            margin-right: 10px;
        }

        #barcode {
            margin: 8px 0 0 0;
            display: block;
        }
    </style>
</head>

<body>
    <section class="wrapper">
        <header>
            <table>
                <tr>
                    <td width="35" valign="top">
                        <div id="logo-kiri"><img src="{{ public_path('/storage/foto/thum_1679297466.png') }}"
                                width="50"></div>
                    </td>
                    <td valign="top">
                        <h2>Kartu Anggota perpustakaan</h2>
                        <h1>smpn 1 karossa</h1>
                        <p>Karossa, Kec. Karossa, Kabupaten Mamuju, <br> Sulawesi Barat 91512</p>
                        <p>Telp: 032493493 | Email: smpn1karossa@gmail.com</p>
                    </td>
                    <td width="35" align="right" valign="top">
                        <div id="logo-kanan"><img src="{{ public_path('/storage/foto/thum_1679297466.png') }}"
                                width="50"></div>
                    </td>
                </tr>
            </table>
        </header>
        <section id="body">
            <table>
                <tr>
                    <td colspan="3">
                        <h1>MUHAMMAD TSAQIF ATMADEVA</h1>
                    </td>
                </tr>
                <tr>
                    <td width="22%">No. Anggota</td>
                    <td width="1%">:</td>
                    <td width="75%">23423423423</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td>VII C</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>Laki-laki</td>
                </tr>
            </table>

            <div id="foto">

            </div>

            <div id="barcode">
                {!! $barcode !!}
            </div>
        </section>
    </section>
</body>

</html>
