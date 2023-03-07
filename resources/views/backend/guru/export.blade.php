<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="12"><b>NO. ANGGOTA</b></th>
            <th width="14"><b>NO. INDUK</b></th>
            <th width="14"><b>NAMA</b></th>
            <th width="14"><b>JENIS KELAMIN</b></th>
            <th width="14"><b>TEMPAT LAHIR</b></th>
            <th width="14"><b>TANGGAL LAHIR</b></th>
            <th width="14"><b>WALI KELAS</b></th>
            <th width="14"><b>ALAMAT</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
        <tr>
            <td valign="top" align="left">{{ $no++ }}</td>
            <td valign="top">{{ $item->nomor_anggota }}</td>
            <td valign="top">{{ $item->nomor_induk }}</td>
            <td valign="top">{{ $item->nama }}</td>
            <td valign="top">{{ $item->jenis_kelamin }}</td>
            <td valign="top">{{ $item->kota->kota }}</td>
            <td valign="top">{{ $item->tanggal_lahir }}</td>
            <td valign="top">{{ ($item->kelas_id) ? $item->kelas->kelas : '-' }}</td>
            <td valign="top">{{ $item->alamat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
