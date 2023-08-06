<?php

namespace App\Http\Controllers;

use App\Http\Requests\KartukkeditRequest;
use App\Http\Requests\KartukkRequest;
use App\Models\Master_Kk;
use App\Models\Master_Masyarakat;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KartukkController extends Controller
{
    public function index()
    {
        $data = Master_Masyarakat::with('masyarakat')
            ->where('status_keluarga', 'kepala keluarga')
            ->orderBy('nik', 'DESC')->get();

        return view('master_kk', compact('data'))->with('success', '');
    }

    public function update(Request $request, KartukkeditRequest $kartukkeditRequest, $id)
    {
        $validated = $kartukkeditRequest->validated();
        $data = Master_Kk::where('no_kk', $id)->first();
        $data->update([
            'no_kk' => $validated['nokkedit'],
            'alamat' => $validated['alamatkkedit'],
            'rt' => $validated['rtedit'],
            'rw' => $validated['rwedit'],
            'kode_pos' => $validated['kdposedit'],
            'kelurahan' => $validated['keledit'],
            'kecamatan' => $validated['kecedit'],
            'kabupaten' => $validated['kabedit'],
            'provinsi' => $validated['provedit'],
            'kk_tgl' => $validated['tglkkedit'],
        ]);

        return Redirect('masterkk')->with('successedit', '');
    }

    //Untuk Hapus Master KK
    public function delete(Request $request, $id)
    {
        try {
            $data = Master_Kk::where('no_kk', $id);
            $data->delete();

            return Redirect('masterkk')->with('successhapus', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('relation', '');
        }

    }

    public function simpanmasterkk(KartukkRequest $kkrequest)
    {
        $validated = $kkrequest->validated();
        $check = Master_Kk::all();
        foreach ($check as $value) {
            if ($value->no_kk == $validated['no_kk']) {
                return redirect()->back()->with('exist', '');
            }
        }
        $data = Master_Kk::create($validated);

        return redirect('simpankepala/'.$kkrequest->no_kk.'/'.$kkrequest->kepala_keluarga.'/'.$kkrequest->nik);
    }

    public function simpankepalakeluarga(Request $request, $id, $other_id, $nik)
    {
        try {
            $datakepala = Master_Kk::where('no_kk', '=', $id)
                ->first();
            // dd($datakepala);
            $data = new Master_Masyarakat();
            // $uuid = Str::uuid()->toString();
            $data->id_kk = $datakepala->id_kk;
            $data->nik = $nik;
            $data->nama_lengkap = $other_id;
            $data->status_keluarga = 'Kepala Keluarga';
            $data->save();

            return Redirect('masterkk')->with('success', 'Berhasil Disimpan');
        } catch (\Throwable $th) {

        }

        try {
            $data = Master_Masyarakat::with('masyarakat')
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->select('master_kks.id')
                ->first();
        } catch (\Throwable $th) {
        }

    }
}
