<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan_Surat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surats';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = ['id_masyarakat', 'id_surat', 'uuid', 'keterangan', 'id_pengajuan', 'status', 'file_pdf', 'info', 'image_kk', 'image_bukti'];


    public function masyarakat()
    {
        return $this->belongsTo(Master_Masyarakat::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function surat()
    {
        return $this->belongsTo(Master_Surat::class, 'id_surat', 'id_surat');
    }

    public function pengajuan()
    {
        return $this->join('master_surats', 'pengajuan_surats.id_surat', '=', 'master_surats.id_surat')
        ->join('master_masyarakats', 'master_masyarakats.id_masyarakat', '=', 'pengajuan_surats.id_masyarakat')
        ->join('master_kks', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
        ->select('master_kks.*', 'master_masyarakats.*', 'pengajuan_surats.id_pengajuan', 'pengajuan_surats.status', 'pengajuan_surats.no_pengantar', 'pengajuan_surats.keterangan', 'pengajuan_surats.created_at', 'pengajuan_surats.image_kk', 'pengajuan_surats.image_bukti', 'pengajuan_surats.nomor_surat', 'master_surats.id_surat', 'pengajuan_surats.keterangan_ditolak', 'master_surats.nama_surat');
    }
    public function UpdateStatus()
    {
        return $this->join('master_masyarakats', 'pengajuan_surats.id_masyarakat', 'master_masyarakats.id_masyarakat')
            ->select('master_masyarakats.*', 'pengajuan_surats.id_pengajuan');
    }
}
