<?php

namespace App\Http\Controllers;

use App\Models\Master_Kk;
use App\Models\Master_Masyarakat;
use App\Models\Pengajuan_Surat;
use Illuminate\Http\Request;

class ApiPengajuanRwController extends Controller
{
    //rekap surat rw sesuai status
    public function status_surat_rw(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $status = $request->input('status');
        $userRole = $request->user()->role;

        $userMasyarakat = Master_Masyarakat::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = Master_Kk::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 3) {
                    return response()->json([
                        'message' => 'Anda bukan rw'
                    ]);
                } else {
                    $suratData = Pengajuan_Surat::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rw', $userKks->rw);
                    })
                    ->with(['masyarakat.akun', 'surat'])
                    ->when($status, function ($query, $status) {
                        return $query->where('status', $status);
                    })
                    ->get();

                    return response()->json($suratData);
                }
            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }

    }

    //rekap semua surat rw
    public function rekap_rw(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        $userMasyarakat = Master_Masyarakat::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = Master_Kk::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 3) {
                    return response()->json([
                        'message' => 'Anda bukan rw'
                    ]);
                } else {
                    $suratData = Pengajuan_Surat::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rw', $userKks->rw);
                    })
                    ->with(['masyarakat', 'surat'])
                    ->whereNotIn('status', ['Dibatalkan'])
                    ->orderByDesc('id_pengajuan')
                    ->paginate(10);


                    return response()->json($suratData);
                }
            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }

    }

    public function update_status_setuju_rw(Request $request, $id)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;


        $userMasyarakat = Master_Masyarakat::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = Master_Kk::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 3) {
                    return response()->json([
                        'message' => 'Anda bukan rw'
                    ]);
                } else {

                    $surat = Pengajuan_Surat::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rw', $userKks->rw);
                    })->findOrFail($id);

                    $surat->status = "Disetujui RW";
                    $surat->save();

                    return response()->json([
                        'message' => 'Status surat updated successfully',
                        'surat' => $surat
                    ]);
                }

            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }
    }
}
