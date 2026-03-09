<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $table    = 'push_subscriptions';
    public $timestamps  = false;

    protected $fillable = [
        'nim', 'endpoint', 'public_key', 'auth_token', 'device_info',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}