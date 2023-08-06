<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_Kk extends Model
{
    use HasFactory;

    protected $table = 'master_kks';
    protected $primaryKey = 'id_kk';
    protected $fillable = [
        'no_kk',
        'id_kk',
        'alamat', 'rt', 'rw', 'kode_pos', 'kelurahan', 'kecamatan', 'kabupaten',
        'provinsi', 'kk_tgl', 'created_at', 'updated_at',
    ];

    public function masyarakat()
    {
        return $this->hasMany(Master_Masyarakat::class, 'id_kk', 'id_kk');
    }

    public function rw()
    {
        return $this->join('master_masyarakats', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
            ->join('master_akuns', 'master_masyarakats.id_masyarakat', 'master_akuns.id_masyarakat')
            ->select('master_kks.*', 'master_masyarakats.*', 'master_akuns.*');
    }
}
