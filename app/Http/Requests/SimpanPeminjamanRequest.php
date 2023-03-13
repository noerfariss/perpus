<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanPeminjamanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'anggota' => 'required',
            'kode_buku_arr' => 'required|array',
            'kode_transaksi' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'anggota.required' => 'Data anggota masih kosong',
            'kode_buku_arr.required' => 'Item buku belum dimasukkan',
            'kode_buku_arr.array' => 'Item buku salah',
            'kode_transaksi.required' => 'Kode transaksi tidak tersedia',
        ];
    }
}
