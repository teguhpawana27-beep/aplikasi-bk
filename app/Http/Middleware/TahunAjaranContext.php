<?php

namespace App\Http\Middleware;

use App\Models\TahunAjaran;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TahunAjaranContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $tahunAjaranId = session('tahun_ajaran_id');

        /*
        |--------------------------------------------------------------------------
        | Jika session belum memiliki tahun ajaran
        |--------------------------------------------------------------------------
        | Gunakan tahun ajaran yang is_active = true.
        |--------------------------------------------------------------------------
        */
        if (!$tahunAjaranId) {
            $tahunAjaranAktif = TahunAjaran::where('is_active', true)
                ->orderByDesc('tanggal_mulai')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Jika belum ada yang aktif, gunakan tahun ajaran terbaru.
            |--------------------------------------------------------------------------
            */
            if (!$tahunAjaranAktif) {
                $tahunAjaranAktif = TahunAjaran::orderByDesc('tanggal_mulai')
                    ->first();
            }

            if ($tahunAjaranAktif) {
                session([
                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil tahun ajaran berdasarkan session
        |--------------------------------------------------------------------------
        */
        $tahunAjaranAktif = null;

        if (session('tahun_ajaran_id')) {
            $tahunAjaranAktif = TahunAjaran::find(
                session('tahun_ajaran_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Jika tahun yang tersimpan sudah tidak tersedia
        |--------------------------------------------------------------------------
        */
        if (!$tahunAjaranAktif) {
            $tahunAjaranAktif = TahunAjaran::where('is_active', true)
                ->orderByDesc('tanggal_mulai')
                ->first();

            if (!$tahunAjaranAktif) {
                $tahunAjaranAktif = TahunAjaran::orderByDesc('tanggal_mulai')
                    ->first();
            }

            if ($tahunAjaranAktif) {
                session([
                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Buat tersedia di semua Blade
        |--------------------------------------------------------------------------
        */
        view()->share(
            'tahunAjaranAktif',
            $tahunAjaranAktif
        );

        view()->share(
            'daftarTahunAjaran',
            TahunAjaran::orderByDesc('tanggal_mulai')->get()
        );

        /*
        |--------------------------------------------------------------------------
        | Buat tersedia melalui Request
        |--------------------------------------------------------------------------
        */
        $request->attributes->set(
            'tahun_ajaran',
            $tahunAjaranAktif
        );

        $request->attributes->set(
            'tahun_ajaran_id',
            $tahunAjaranAktif?->id
        );

        return $next($request);
    }
}