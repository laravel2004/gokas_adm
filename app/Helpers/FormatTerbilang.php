<?php

if (!function_exists('terbilang')) {
    function terbilang($angka)
    {
        $angka = (int) $angka;
        $huruf = [
            '',
            'SATU',
            'DUA',
            'TIGA',
            'EMPAT',
            'LIMA',
            'ENAM',
            'TUJUH',
            'DELAPAN',
            'SEMBILAN',
            'SEPULUH',
            'SEBELAS'
        ];

        if ($angka < 12) {
            return $huruf[$angka];
        } elseif ($angka < 20) {
            return $huruf[$angka - 10] . ' BELAS';
        } elseif ($angka < 100) {
            return terbilang((int)($angka / 10)) . ' PULUH' . ($angka % 10 ? ' ' . terbilang($angka % 10) : '');
        } elseif ($angka < 200) {
            return 'SERATUS' . ($angka % 100 ? ' ' . terbilang($angka % 100) : '');
        } elseif ($angka < 1000) {
            return terbilang((int)($angka / 100)) . ' RATUS' . ($angka % 100 ? ' ' . terbilang($angka % 100) : '');
        } elseif ($angka < 1000000) {
            return terbilang((int)($angka / 1000)) . ' RIBU' . ($angka % 1000 ? ' ' . terbilang($angka % 1000) : '');
        } elseif ($angka < 1000000000) {
            return terbilang((int)($angka / 1000000)) . ' JUTA' . ($angka % 1000000 ? ' ' . terbilang($angka % 1000000) : '');
        }

        return $angka;
    }
}
