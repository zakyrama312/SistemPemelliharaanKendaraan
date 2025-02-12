<?php

namespace App\Helpers;

use Carbon\Carbon;

class FormatHelper
{
    public static function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public static function formatTanggal($tanggal)
    {
        return Carbon::parse($tanggal)->translatedFormat('l, d F Y');
    }
}