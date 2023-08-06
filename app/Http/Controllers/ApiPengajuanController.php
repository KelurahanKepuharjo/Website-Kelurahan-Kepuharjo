<?php

namespace App\Http\Controllers;

use App\Models\Master_Akun;
use App\Models\Master_Kk;
use App\Models\Master_Masyarakat;
use App\Models\Pengajuan_Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;

class ApiPengajuanController extends Controller
{
    public function pengajuan(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'keterangan' => 'required',
            'id_surat' => 'required',
            'image_kk' => 'required|image',
            'image_bukti' => 'required|image',
        ]);
        $masyarakat = Master_Masyarakat::where('nik', $request->nik)->first();

        $imagekk = $request->file('image_kk');
        $imagebukti = $request->file('image_bukti');

        $imagenamekk = "img_" . Str::random(10) . time() . '.' . $imagekk->getClientOriginalExtension();
        $imagenamebukti = "img_" . Str::random(10) . time() . '.' . $imagebukti->getClientOriginalExtension();

        $imagekk->move(public_path('images/'), $imagenamekk);
        $imagebukti->move(public_path('images/'), $imagenamebukti);
        $cek = Pengajuan_Surat::where('id_surat', $request->id_surat)
            ->where('id_masyarakat', $masyarakat->id_masyarakat)
            ->where('info', 'active')
            ->exists();

        if ($cek) {
            return response()->json([
                'message' => 'Surat sebelumnya belum selesai',
            ], 200);
        } else {
            $data = Pengajuan_Surat::create([
                'keterangan' => $request->keterangan,
                'status' => 'Diajukan',
                'info' => 'active',
                'uuid' => Str::uuid(),
                'id_surat' => $request->id_surat,
                'image_kk' => $imagenamekk,
                'image_bukti' => $imagenamebukti,
                'id_masyarakat' => $masyarakat->id_masyarakat,
            ]);

            return response()->json([
                'message' => 'Berhasil mengajukan surat',
                'data' => $data
            ], 200);

        }
    }

    public function status_proses(Request $request)
    {
        $user = $request->user();
        $id_masyarakat = $user->id_masyarakat;

        $no_kk = Master_Kk::whereHas('masyarakat', function ($query) use ($id_masyarakat) {
            $query->where('id_masyarakat', $id_masyarakat);
        })->value('no_kk');

        $pengajuan_surats = Pengajuan_Surat::with('surat', 'masyarakat.kks')
        ->where(function ($query) use ($id_masyarakat, $no_kk) {
            $query->where('id_masyarakat', $id_masyarakat)
                ->orWhereHas('masyarakat.kks', function ($query) use ($no_kk) {
                    $query->where('no_kk', $no_kk);
                });
        })
            ->whereNotIn('status', ['Selesai', 'Diajukan', 'Dibatalkan', 'Ditolak RT'])
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $pengajuan_surats,
        ], 200);
    }


    public function status_surat(Request $request)
    {
        $user = $request->user();
        $id_masyarakat = $user->id_masyarakat;
        $status = $request->status;

        $no_kk = Master_Kk::whereHas('masyarakat', function ($query) use ($id_masyarakat) {
            $query->where('id_masyarakat', $id_masyarakat);
        })->value('no_kk');

        $pengajuan_surats = Pengajuan_Surat::with('surat', 'masyarakat.kks')
            ->where(function ($query) use ($id_masyarakat, $no_kk) {
                $query->where('id_masyarakat', $id_masyarakat)
                    ->orWhereHas('masyarakat.kks', function ($query) use ($no_kk) {
                        $query->where('no_kk', $no_kk);
                    });
            })
            ->where('status', $status)
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $pengajuan_surats,
        ], 200);
    }

    public function pembatalan(Request $request, $id)
    {
        $userId = $request->user()->id_masyarakat;
        $pengajuanSurat = Pengajuan_Surat::where('id_pengajuan', $id)
            ->where('id_masyarakat', $userId)
            ->where('status', 'Diajukan')
            ->firstOrFail();

        $pengajuanSurat->update(['status' => 'Dibatalkan', 'info' => 'non_active']);

        return response()->json([
            'message' => 'Surat berhasil dibatalkan',
        ], 200);
    }

    public function sendNotification(Request $request)
    {
        // Langkah 1: Mendapatkan user id_masyarakat dari Request
        $userId = $request->user()->id_masyarakat;

        // Langkah 2: Mencari data masyarakat berdasarkan id_masyarakat
        $userMasyarakat = Master_Masyarakat::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            // Langkah 3: Mencari akun dengan rt yang sama dan role bernilai 2
            $userWithSameRt = Master_Akun::whereHas('masyarakat', function ($query) use ($userMasyarakat) {
                $query->where('id_kk', $userMasyarakat->id_kk);
            })->where('role', 2)->first();

            if ($userWithSameRt) {
                $token = $userWithSameRt->fcm_token;

                try {
                    $message = CloudMessage::new()
                        ->withNotification(Notification::create("Surat Masuk", "Terdapat surat masuk"))
                        ->withChangedTarget('token', $token);

                    FirebaseMessaging::send($message);
                    return response()->json(['message' => 'Notification sent successfully.']);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Failed to send notification.'], 500);
                }
            } else {
                return response()->json(['message' => 'User with same rt and role 2 not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'User Masyarakat not found.'], 404);
        }
    }
}
