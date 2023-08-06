<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        session()->flush();

        return view('login');
    }

    public function store(Request $request)
    {
        // Validasi input
        // dd($request->session()->get('_token'));

        // Memeriksa apakah user adalah admin
        if ($request->username == 'admin' && $request->password == 'admin') {
            $session = [
                'nama' => 'Kepuharjo',
                'hak_akses' => 'admin',
                'rt' => '',
                'rw' => '',
            ];
            session()->put($session);

            // Menambahkan CSRF token ke dalam session
            $token = csrf_token();
            Session::put('_token', $token);

            return redirect('dashboard');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Memeriksa apakah user adalah masyarakat
        $user = DB::table('master_masyarakats')
            ->join('master_akuns', 'master_akuns.id_masyarakat', '=', 'master_masyarakats.id_masyarakat')
            ->join('master_kks', 'master_kks.id', '=', 'master_masyarakats.id')
            ->where('master_masyarakats.nik', '=', $request->username)
            ->first();

        if (! $user) {
            return redirect()->back()->withErrors(['username' => 'Username yang anda masukkan salah'])->withInput();
        } elseif ($request->password != null) {
            Hash::check($request->password, $user->password);
            $session = [
                'nik' => $user->nik,
                'nama' => $user->nama_lengkap,
                'hak_akses' => $user->role,
                'rt' => $user->rt,
                'rw' => $user->rw,
            ];
            session()->put($session);

            // Menambahkan CSRF token ke dalam session
            $token = csrf_token();
            Session::put('_token', $token);

            return redirect('dashboard');
        } else {
            return redirect()->back()->withErrors(['password' => 'Password yang anda masukkan salah'])->withInput();
        }
    }
}
