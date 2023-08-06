<?php

namespace App\Http\Controllers;

use App\Models\Master_Surat;
use Illuminate\Http\Request;

class ApiSuratController extends Controller
{
    public function surat()
    {
        $surat = Master_Surat::orderByDesc('id_surat')->get();

        return response()->json([
            'message' => 'success',
            'data' => $surat,
        ]);
    }
}
