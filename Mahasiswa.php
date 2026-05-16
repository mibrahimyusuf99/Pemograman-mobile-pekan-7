<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nim', 'nama', 'jenis_kelamin',
        'kelas', 'jurusan', 'tahun_masuk',
        'agama', 'alamat_asal', 'alamat_sekarang',
        'foto', 'link_ig', 'link_linkedin',
    ];
}