# PERPUS PROJECT 
* Laravel 9
* mariaDB 10
* PHP min. 8.0.25

## DOKUMENTASI API

### Auth
- Login

```
{{baseUrl}}/login

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

- Logout

### Profil
- ShowUser
- EditUser
- EditPassword
- EditFoto

### Buku
- KategoriByKelas
- DetailBuku
