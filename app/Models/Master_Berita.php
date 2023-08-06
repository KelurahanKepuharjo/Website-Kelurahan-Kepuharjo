<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_Berita extends Model
{
    use HasFactory;

    protected $table = 'master_beritas';

    protected $fillable = ['judul', 'sub_title', 'deskripsi', 'image'];
}
