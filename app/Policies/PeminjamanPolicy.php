<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        $anggota = Anggota::where('email', $user->email)->first();

        return $anggota !== null && $peminjaman->anggota_id === $anggota->id;
    }
}