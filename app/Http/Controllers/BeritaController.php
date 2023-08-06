<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeritaRequest;
use App\Models\Master_Berita;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $data = Master_Berita::orderByDesc('id');

        return view('berita', compact('data'));
    }

    public function store(BeritaRequest $beritaRequest)
    {
        $imageName = time().'.'.$beritaRequest->image->getClientOriginalExtension();
        $beritaRequest->image->move(public_path('images'), $imageName);
        $validated['image'] = $imageName;
        $validated = $beritaRequest->validated();
        $Berita = Master_Berita::create([
            'judul' => $validated['judul'],
            'sub_title' => $validated['sub_title'],
            'deskripsi' => $validated['deskripsi'],
            'image' => $imageName,
        ]);
        if ($Berita) {
            $message = CloudMessage::new()
            ->withNotification(Notification::create("Berita", "Berita terbaru terkait kelurahan kepuharjo", ))
            ->withChangedTarget('topic', 'all');

            FirebaseMessaging::send($message);
            return Redirect('berita')->with('success', '');

        }
        // return Redirect('berita')->with('success', '');
    }

    public function update(BeritaRequest $beritaRequest, $id)
    {
        // dd($beritaRequest);
        $berita = Master_Berita::where('id', $id)->first();
        // $imageName = time().'.'.$beritaRequest->image->getClientOriginalExtension();
        // $beritaRequest->image->move(public_path('images'), $imageName);
        // $validated['image'] = $imageName;
        $validated = $beritaRequest->validated();
        $berita->update([
            'judul' => $validated['judul'],
            'sub_title' => $validated['sub_title'],
            'deskripsi' => $validated['deskripsi'],

        ]);

        return back()->with('successedit', '');
    }

    public function updateimage(Request $beritaRequest, $id)
    {
        request()->validate(
            [
            'image' => 'required|image|mimes: jpeg,png,jpg,gif,svg|max:2048'
        ],
            [
            'image.required' => 'Gambar Surat Harus diisi',
            'image.mimes' => 'Format Ikon Harus jpeg,png,jpg,gif,svg',
            'image.max'=> 'Ukuran Ikon Maksimal 2 Mb'
        ]
        );

        $imageName = time().'.'.$beritaRequest->image->getClientOriginalExtension();
        $beritaRequest->image->move(public_path('images'), $imageName);
        $berita = Master_Berita::where('id', $id);
        $berita->update([
            'image' => $imageName
        ]);

        return back()->with('successedit', '');
    }

    public function delete(Request $request, $id)
    {
        $data = Master_Berita::where('id', $id);
        $data->delete();

        return Redirect('berita')->with('successhapus', '');
    }
}
