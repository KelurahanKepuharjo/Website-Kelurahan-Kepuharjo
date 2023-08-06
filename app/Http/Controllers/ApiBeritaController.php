<?php

namespace App\Http\Controllers;

use App\Models\Master_Berita;
use Illuminate\Http\Request;

class ApiBeritaController extends Controller
{
    public function berita()
    {
        $berita = Master_Berita::orderByDesc('id')->get();

        return response()->json([
            'message' => 'success',
            'data' => $berita,
        ]);
    }
}
