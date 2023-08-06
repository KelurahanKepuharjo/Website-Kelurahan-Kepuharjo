<?php

namespace App\Http\Controllers;

use App\Models\UpdateStatusModel;
use App\Http\Requests\PengajuanRequest;
use App\Models\Pengajuan_Surat;
use FPDF;

class PengajuanController extends Controller
{
    public function surat_masuk()
    {
        $pengajuan = new Pengajuan_Surat();
        $data = $pengajuan->pengajuan()
            ->where('pengajuan_surats.status', '=', 'Disetujui RW')
            ->get();

        return view('surat_masuk', compact('data'));
    }

    public function update_status(PengajuanRequest $request, $id, $akses)
    {
        $status = 'Selesai';
        $validated = $request->validated();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pengajuan = new Pengajuan_Surat();
        $data = $pengajuan->pengajuan()
        ->where('pengajuan_surats.id_pengajuan', $id)->get();
        foreach ($data as $user) {

            $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');
            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(30, 24);
            $pdf->MultiCell(
                0,
                6,
                '
                P E M E R I N T A H   K A B U P A T E N  L U M A J A N G
                KECAMATAN LUMAJANG
                KELURAHAN KEPUHARJO
                Jl. Langsep No. 18 Telp. (0334) 888243
                L U M A J A N G

                ',
                0,
                'C',
                false,
                20
            );
            $pdf->SetFont('Times', 'B', 14);
            $pdf->SetXY(20, 66);
            $pdf->MultiCell(
                0,
                6,
                "SURAT KETERANGAN $user->nama_surat
                    ",
                0,
                'C',
                false,
                20
            );

            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(20, 72);

            $pdf->MultiCell(
                0,
                6,
                "NOMOR : $request->nomor_surat
                    ",
                0,
                'C',
                false,
                20
            );
            $pdf->SetFont('Times', '', 12);
            $pdf->SetXY(20, 84);
            $pdf->MultiCell(
                0,
                6,
                '             Yang bertanda tangan di bawah ini kami Lurah Kepuharjo Kecamatan Lumajang Kabupaten Lumajang menerangkan bahwa : ',
                0,
                'L',
                false,
                20
            );

            $pdf->SetXY(42, 102);
            $pdf->MultiCell(
                0,
                6,
                "            Nama                            : $user->nama_lengkap
            Tempat,Tgl Lahir         : $user->tempat_lahir ,$user->tgl_lahir
            Jenis Kelamin               : $user->jenis_kelamin
            Kebangsaan / Agama    : $user->kewarganegaraan , $user->agama
            Status 	                          : $user->status_perkawinan
            Pekerjaan 	                    : $user->pekerjaan
            NIK	                              : $user->nik
            Alamat 	                        : $user->alamat
            ",
                0,
                'L',
                false,
                20
            );
            $pdf->Image('image/stempel.png', 120, 216, 33, 0, 'PNG');
            $pdf->Image('image/ttd.png', 110, 220, 40, 0, 'PNG');
            $pdf->SetXY(20, 150);
            $pdf->MultiCell(
                0,
                6,
                "                                                                        Kelurahan Kepuharjo Kecamatan Lumajang

            Adalah benar sampai dengan saat ini warga kami dan berdasarkan Surat pengantar Nomer. $user->no_pengantar   Tanggal, $user->created_at  dan pengakuannya. Bahwa nama yang tersebut diatas benar keluarga tidak mampu. Surat keterangan ini hanya dipergunakan untuk $user->keterangan

            Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinnya.


                                                                                        Lumajang, $user->created_at
                                                                                        LURAH KEPUHARJO




                                                                                        MUHAMMAD SAIFUL,S.AP
                                                                                        NIP. 19720202 199803 1 010

                            ",
                0,
                'L',
                false,
                20
            );
            $pdf->Output(public_path('pdf/'.$user->nama_lengkap.'_'.$user->nik.'_'.$user->nama_surat.'_'.$id.'.pdf'), 'F');
            $updatestatus = new Pengajuan_Surat();
            $data = $updatestatus->where('id_pengajuan', $id)
            ->update([
                'nomor_surat' => $validated['nomor_surat'],
                'status' => $status,
                'info' => 'non_active',
                'file_pdf' => $user->nama_lengkap.'_'.$user->nik.'_'.$user->nama_surat.'_'.$id.'.pdf'
            ]);
            return redirect('/suratmasuk')->with('successedit', '');
        }
    }

    public function surat_selesai()
    {
        if (session('hak_akses') == 'admin') {
            $pengajuan = new Pengajuan_Surat();
            $data = $pengajuan->pengajuan()
                ->where('pengajuan_surats.status', '=', 'Selesai')
                ->get();
        }

        return view('surat_selesai', compact('data'));
    }
}
