# PERPUS PROJECT 
* Laravel 9
* mariaDB 10
* PHP min. 8.0.25

## DOKUMENTASI API

### Auth
- Login ===== ![#f03c15](https://placehold.co/15x15/f03c15/f03c15.png) POST

```
{{baseUrl}}/login

// Parameter
{
    "anggota": "2023KS0004",
    "password": "password"
}

// Return Success ---- 200
{
    "message": "Success",
    "success": true,
    "data": [] <----- isi datanyaaa
    "token": "27|EFqVrgrcB5XtQMppHr5BFlnagOj2DlU23U6ZV0uO"
}

// Return Errors ---- bukan 200 (422, 401, 500)
{
    "message": "Error",
    "success": false,
    "errors": [] <----- isi data errornya
}
```

- Logout ===== ![#1589F0](https://placehold.co/15x15/1589F0/1589F0.png) GET
```
{{baseUrl}}/logout

// Parameter
{
    "Authorization ": "Bearer isi-token-nya",
}

// Return Success ---- 200
{
    "message": "Success",
    "success": true,
    "data": [] <----- isi datanyaaa
}

// Return Errors ---- 401
{
    "message": "Unauthenticated.",
}
```

### Profil
- ShowUser ===== ![#1589F0](https://placehold.co/15x15/1589F0/1589F0.png) GET
```
{{baseUrl}}/user

// Parameter
{
    "Authorization ": "Bearer isi-token-nya",
}

// Return Success ---- 200
{
    "message": "Data berhasil ditemukan",
    "success": true,
    "data": [] <----- isi datanyaaa
}

// Return Errors ---- 401
{
    "message": "Unauthenticated.",
}
```

- EditUser ===== ![#c5f015](https://placehold.co/15x15/c5f015/c5f015.png) PUT
```
{{baseUrl}}/user

// Parameter
{
    "Authorization ": "Bearer isi-token-nya",

    <!-- Raw Data -->
    {
        "nama" : "Elfiansyah",
        "jenis_kelamin" : "L",
        "kota_id" : 1212,
        "tanggal_lahir" : "2007-12-31",
        "kelas_id" : "14",
        "alamat" : "tengah tengah"
    }
}

// Return Success ---- 200
{
    "message": "Data berhasil ditemukan",
    "success": true,
    "data": [] <----- isi datanyaaa
}

// Return Errors ---- 401
{
    "message": "Unauthenticated.",
}
```

- EditPassword
- EditFoto

### Buku
- KategoriByKelas
- DetailBuku

