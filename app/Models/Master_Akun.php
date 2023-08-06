<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Master_Akun extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'master_akuns';
    protected $primaryKey = 'akun_id';

    protected $fillable = [
        'akun_id', 'uuid', 'password', 'no_hp', 'role', 'fcm_token', 'id_masyarakat',
    ];

    public function user()
    {
        return $this->hasOne(Master_Masyarakat::class, 'id_masyarakat', 'id_masyarakat')
            ->join('master_kks', 'master_kks.id_kk', '=', 'master_masyarakats.id_kk');
    }

    public function masyarakat()
    {
        return $this->belongsTo(Master_Masyarakat::class, 'id_masyarakat', 'id_masyarakat');
    }
}
