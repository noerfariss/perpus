<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="14"><b>JUDUL</b></th>
            <th width="14"><b>KATEGORI</b></th>
            <th width="14"><b>PENGARANG</b></th>
            <th width="14"><b>PENERBIT</b></th>
            <th width="14"><b>ISBN</b></th>
            <th width="14"><b>STOK</b></th>
            <th width="14"><b>DIPINJAM</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
        <tr>
            <td valign="top" align="left">{{ $no++ }}</td>
            <td valign="top">{{ $item->judul }}</td>
            <td valign="top">{{ $item->kategori->implode('kategori', ', ') }}</td>
            <td valign="top">{{ $item->pengarang }}</td>
            <td valign="top">{{ $item->penerbit->penerbit }}</td>
            <td valign="top">{{ $item->isbn }}</td>
            <td valign="top">{{ $item->stok }}</td>
            <td valign="top">{{ count($item->buku_item) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
