<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Check if the user has one of the given roles.
     *
     * @param  UserRole|string  ...$roles
     */
    public function hasRole(UserRole|string ...$roles): bool
    {
        foreach ($roles as $role) {
            $roleValue = $role instanceof UserRole ? $role->value : $role;
            if ($this->role->value === $roleValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * Super Admin only: can manage user accounts.
     */
    public function canManageUsers(): bool
    {
        return $this->hasRole(UserRole::SuperAdmin);
    }

    /**
     * Super Admin, Psikologi, Staff: can access and manage Identitas Siswa.
     */
    public function canAccessIdentitas(): bool
    {
        return $this->hasRole(UserRole::SuperAdmin, UserRole::Psikologi, UserRole::Staff);
    }

    /**
     * Super Admin, Psikologi: full access to create/edit/delete counseling cases, handling, follow-up & summary.
     */
    public function canManageCounselingCases(): bool
    {
        return $this->hasRole(UserRole::SuperAdmin, UserRole::Psikologi);
    }

    /**
     * All authenticated roles (Super Admin, Psikologi, Staff, Atasan): can access Rekap Kasus.
     */
    public function canAccessRekap(): bool
    {
        return $this->hasRole(UserRole::SuperAdmin, UserRole::Psikologi, UserRole::Staff, UserRole::Atasan);
    }

    /**
     * Super Admin, Psikologi, Atasan: can access Laporan Bulanan BK.
     */
    public function canAccessLaporan(): bool
    {
        return $this->hasRole(UserRole::SuperAdmin, UserRole::Psikologi, UserRole::Atasan);
    }
}
