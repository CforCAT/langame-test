<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'code',
        'verified',
        'expires_at'
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verified' => 'bool',
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }
}
