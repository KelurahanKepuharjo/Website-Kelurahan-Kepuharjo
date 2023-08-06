<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_Surat extends Model
{
    use HasFactory;

    protected $table = 'master_surats';

    protected $primaryKey = 'id_surat';

    protected $fillable = ['id_surat', 'nama_surat', 'image'];

    public function pengajuan_surats()
    {
        return $this->hasMany(Pengajuan_Surat::class, 'id_surat', 'id_surat');
    }

}
