<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $table = 'admin';

    protected $fillable = [
        'username', 'password', 'nama', 'email', 'role',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
