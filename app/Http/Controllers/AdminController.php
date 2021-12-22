<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $data_siswa = Siswa::all();
        $data_presensi = Presensi::all();

        return view('pages/admin/index', compact('data_siswa', 'data_presensi'));
    }
}
