<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? null;

        $data = [];
        $grandTotal = [];

        if ($tahun) {
            $data = DB::table('t_pesanan_detail as d')
                ->join('t_pesanan as p', 'p.id', '=', 'd.t_pesanan_id')
                ->join('m_menu as m', 'm.id', '=', 'd.m_menu_id')
                ->select(
                    'm.nama',
                    'm.kategori',
                    DB::raw('MONTH(p.tanggal) as bulan'),
                    DB::raw('SUM(d.total) as total')
                )
                ->whereYear('p.tanggal', $tahun)
                ->groupBy('m.nama', 'm.kategori', 'bulan')
                ->get()
                ->groupBy(['kategori', 'nama']);


            $grandTotal = DB::table('t_pesanan_detail as d')
                ->join('t_pesanan as p', 'p.id', '=', 'd.t_pesanan_id')
                ->select(DB::raw('MONTH(p.tanggal) as bulan'), DB::raw('SUM(d.total) as total'))
                ->whereYear('p.tanggal', $tahun)
                ->groupBy('bulan')
                ->pluck('total', 'bulan');
        }


        return view('laporan', compact('data', 'tahun', 'grandTotal'));
    }

    public function downloadDatabase()
{
    $filename = 'db_penjualan_' . date('Y-m-d') . '.sql';
    $tables = ['m_menu', 't_pesanan', 't_pesanan_detail'];

    $content = "-- Export Database --\n";
    $content .= "-- Waktu Export: " . date('Y-m-d H:i:s') . "\n\n";

    foreach ($tables as $table) {
        $rows = DB::table($table)->get();

        foreach ($rows as $row) {
            $rowArray = (array) $row;

            $values = array_map(function ($value) {
                if ($value === null) {
                    return 'NULL';
                }
                return "'" . addslashes($value) . "'";
            }, $rowArray);

            $content .= "INSERT INTO {$table} VALUES (" . implode(', ', $values) . ");\n";
        }

        $content .= "\n";
    }

    return response($content)
        ->header('Content-Type', 'application/sql')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}

}
