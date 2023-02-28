<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="12"><b>USERNAME</b></th>
            <th width="14"><b>NAMA</b></th>
            <th width="20"><b>EMAIL</b></th>
            <th width="14"><b>TELPON</b></th>
            <th width="10"><b>ROLE</b></th>

            @if ($request['ext'] == 'foto')
            <th width="20"><b>FOTO</b></th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
        <tr>
            <td valign="top" align="left">{{ $no++ }}</td>
            <td valign="top">{{ $item->username }}</td>
            <td valign="top">{{ $item->nama }}</td>
            <td valign="top">{{ $item->email }}</td>
            <td valign="top">{{ $item->user_telpon }}</td>
            <td valign="top">{{ $item->role_user->implode('name', ', ') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
