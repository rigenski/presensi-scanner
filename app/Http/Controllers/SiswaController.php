<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    public function index()
    {
        $data_siswa = Siswa::all();

        return view('pages/admin/siswa/index', compact('data_siswa'));
    }

    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $validator = Validator::make($request->all(), [
            'card_id' => 'required|unique:siswa',
            'nis' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
            'foto' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.siswa')->with('error', 'Siswa gagal ditambahkan');
        }

        $siswa = Siswa::create([
            'card_id' => $request->card_id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
        ]);

        $text_rand = Str::random(20);
        $name_image = $text_rand . "." . $request->file('foto')->getClientOriginalExtension();
        $request->file('foto')->move('images/siswa/', $name_image);
        $siswa->foto = $name_image;
        $siswa->save();

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        date_default_timezone_set("Asia/Jakarta");

        $siswa = Siswa::find($id);

        $validator = Validator::make($request->all(), [
            'card_id' => 'required|unique:siswa,card_id,' . $siswa->id,
            'nis' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.siswa')->with('error', 'Siswa gagal diperbarui');
        }

        $siswa->update([
            'card_id' => $request->card_id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
        ]);

        if ($request->hasFile('foto')) {
            $text_rand = Str::random(20);
            $name_image = $text_rand . "." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move('images/siswa/', $name_image);
            $siswa->foto = $name_image;
            $siswa->save();
        }

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil diperbarui');
    }

    public function destroy($id)
    {
        Siswa::find($id)->delete();

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil dihapus');
    }
}
