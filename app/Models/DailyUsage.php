<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id', 'qty_penggunaan', 'tanggal', 'keterangan', 'user_id',
    ];

    protected $casts = [
        'qty_penggunaan' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}