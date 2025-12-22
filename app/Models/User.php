<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // Tambahkan ini
use Filament\Panel; // Tambahkan ini
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser // Tambahkan implements
{
    // ... kode lainnya

    // Tambahkan method wajib ini
    public function canAccessPanel(Panel $panel): bool
    {
        // Mengizinkan semua user mengakses panel (cocok untuk tahap development)
        return true; 
    }
}