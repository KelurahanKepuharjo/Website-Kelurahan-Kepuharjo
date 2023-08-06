<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Models\Master_Akun;

class UserController extends Controller
{
    public function masteruser()
    {
        $data = Master_Akun::with('user')
            ->where('role', '=', '4')->get();

        return view('master_user', compact('data'));
    }

    public function update(PasswordRequest $passwordrequest, $id)
    {
        $data = Master_Akun::where('id_masyarakat', $id)->first();
        $validated = $passwordrequest->validated();
        $data->update($validated);

        return redirect('masteruser')->with('successedit', '');
    }
}
