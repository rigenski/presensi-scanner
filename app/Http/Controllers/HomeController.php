<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages/index');
    }

    public function indexPulang()
    {
        return view('pages/pulang');
    }

    public function check(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $request->validate([
            'card_id' => 'required',
            'kategori' => 'required',
        ]);

        $card_id = $request->card_id[0] ? $request->card_id[0] : $request->card_id[1];


        $siswa = Siswa::where('card_id', $card_id)->get();

        session(['kategori' => $request->kategori]);

        if (count($siswa)) {
            $siswa = $siswa[0];

            $presensi = Presensi::where('siswa_id', $siswa->id)->where('kategori', $request->kategori)->orderBy('id', 'DESC')->get();

            if (count($presensi) == 0 || (count($presensi) !== 0 && Carbon::parse($presensi[0]->created_at)->format('Y-m-d') !== date('Y-m-d'))) {
                Presensi::create([
                    'siswa_id' => $siswa->id,
                    'kategori' => $request->kategori
                ]);
            }

            session(['siswa_id' => $siswa->id]);

            return redirect()->route('detail')->with('success', 'success');
        }

        session(['siswa_id' => null]);

        return redirect()->route('detail')->with('failed', 'failed');
    }

    public function indexDetail()
    {
        $kategori = session()->get('kategori');

        if (session()->get('siswa_id')) {
            $siswa_id = session()->get('siswa_id');

            $siswa = Siswa::find($siswa_id);

            return view('/pages/detail', compact('siswa', 'kategori'));
        }

        return view('pages/detail', compact('kategori'));
    }

    public function indexGrafik()
    {
        date_default_timezone_set("Asia/Jakarta");

        $data_presensi = Presensi::whereDate('created_at', Carbon::today())->select([
            DB::raw('id'),
            DB::raw('kategori')
        ])->get()->toArray();

        $data_presensi = json_encode($data_presensi);


        return view('pages/grafik', compact('data_presensi'));
    }

    public function print()
    {
        date_default_timezone_set("Asia/Jakarta");

        $data_siswa = Siswa::all();
        $total_masuk = 0;

        $html = "
                    <html>
                        <head>

                        </head>
                        <body>

                        </body>
                    </html>
                ";

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [44, 84],
            'default_font_size' => '8',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'default_font' => 'sans-serif',
            'orientation' => 'P',
        ]);

        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($html);

        $mpdf->Image(asset('/images/logo-sekolah.png'), 0, 9, 7.6, 8.8, 'png', '', true, false);

        $mpdf->SetFont('Arial', 'B', 6.4);
        $mpdf->setXY(18, 1);
        $mpdf->WriteCell(6.4, 0.4, '======', 0, 'C');

        $mpdf->SetFont('Arial', 'B', 8.6);
        $mpdf->setXY(10, 10);
        $mpdf->WriteCell(6.4, 0.4, 'PRESENSI SISWA', 0, 'C');
        $mpdf->setXY(10, 12.8);
        $mpdf->WriteCell(6.4, 0.4, 'SMK MUHAMMADIYAH', 0, 'C');
        $mpdf->setXY(10, 15.6);
        $mpdf->WriteCell(6.4, 0.4, '1 SUKOHARJO', 0, 'C');

        $mpdf->SetFont('Arial', 'B', 8.6);
        $mpdf->setXY(11, 24);
        $mpdf->WriteCell(6.4, 0.4, 'KELAS : XII RPL', 0, 'C');

        $mpdf->SetFont('Arial', 'B', 7.4);
        $mpdf->setXY(0, 30);
        $mpdf->WriteCell(6.4, 0.4, 'NIS', 0, 'C');
        $mpdf->setXY(10, 30);
        $mpdf->WriteCell(6.4, 0.4, 'NAMA', 0, 'C');
        $mpdf->setXY(38.8, 30);
        $mpdf->WriteCell(6.4, 0.4, 'KET', 0, 'C');

        $height = 33.4;
        foreach ($data_siswa as $siswa) {
            $presensi = Presensi::where('siswa_id', $siswa->id)->where('kategori', 'masuk')->whereDate('created_at', Carbon::today())->select([
                DB::raw('id'),
                DB::raw('kategori')
            ])->get()->toArray();

            $mpdf->SetFont('Arial', '', 7.4);
            $mpdf->setXY(0, $height);
            $mpdf->WriteCell(6.4, 0.4, $siswa->nis, 0, 'C');
            $mpdf->setXY(10, $height);
            $mpdf->WriteCell(6.4, 0.4, substr($siswa->nama, 0, 20), 0, 'C');
            $mpdf->setXY(38.8, $height);
            $mpdf->WriteCell(6.4, 0.4, count($presensi) !== 0 ? 'M' : 'A', 0, 'C');
            $height += 3;

            if (count($presensi) !== 0) {
                $total_masuk += 1;
            }
        }


        $mpdf->SetFont('Arial', 'B', 7.4);
        $mpdf->setXY(0, 62.6);
        $mpdf->WriteCell(6.4, 0.4, 'DETAIL', 0, 'C');

        $mpdf->SetFont('Arial', '', 7.4);
        $mpdf->setXY(0, 66);
        $mpdf->WriteCell(6.4, 0.4, 'Total Siswa : ' . count($data_siswa), 0, 'C');
        $mpdf->setXY(0, 69);
        $mpdf->WriteCell(6.4, 0.4, 'Siswa Masuk : ' . $total_masuk, 0, 'C');
        $mpdf->setXY(0, 72);
        $mpdf->WriteCell(6.4, 0.4, 'Siswa Alfa : ' . (count($data_siswa) - $total_masuk), 0, 'C');

        $mpdf->SetFont('Arial', 'B', 6.4);
        $mpdf->setXY(18, 82);
        $mpdf->WriteCell(6.4, 0.4, '======', 0, 'C');



        $mpdf->Output('Presensi Siswa Kelas XII RPL - SMK Muhammadiyah 1 Sukoharjo' . '.pdf', 'I');
        exit;

        return redirect()->route('grafik')->with('success', 'succes');
    }
}
