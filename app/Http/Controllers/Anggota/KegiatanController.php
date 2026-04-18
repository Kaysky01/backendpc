<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $attendedIds = $request->user()->absensis()->pluck('kegiatan_id')->all();

        $kegiatan = Kegiatan::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('anggota.kegiatan.index', [
            'kegiatan' => $kegiatan,
            'search' => $search,
            'attendedIds' => $attendedIds,
        ]);
    }
}
