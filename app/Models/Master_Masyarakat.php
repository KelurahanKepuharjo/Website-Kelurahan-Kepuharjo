<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_Masyarakat extends Model
{
    use HasFactory;

    protected $table = 'master_masyarakats';

    protected $primaryKey = 'id_masyarakat';

    protected $fillable = [
        'id_masyarakat', 'uuid', 'nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir',
        'tgl_lahir', 'agama', 'pendidikan', 'pekerjaan', 'golongan_darah', 'status_perkawinan', 'tgl_perkawinan',
        'status_keluarga', 'kewarganegaraan', 'no_paspor', 'no_kitap', 'nama_ayah', 'nama_ibu', 'id_kk', 'created_at', 'updated_at',
    ];


    public function akun()
    {
        return $this->hasOne(Master_Akun::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function kks()
    {
        return $this->belongsTo(Master_Kk::class, 'id_kk', 'id_kk');
    }

    public function pengajuan_surats()
    {
        return $this->hasMany(Pengajuan_Surat::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function masyarakat()
    {
        return $this->belongsTo(Master_Kk::class, 'id_kk', 'id_kk');
    }
}
