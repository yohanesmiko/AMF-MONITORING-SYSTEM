<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_material', 'nama_material', 'jenis', 'kategori', 'stok',
        'satuan', 'stok_minimum', 'harga_satuan', 'supplier', 'lokasi',
        'deskripsi', 'status',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
        'stok_minimum' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function wipItems()
    {
        return $this->hasMany(WipItem::class);
    }

    public function dailyUsages()
    {
        return $this->hasMany(DailyUsage::class);
    }

    public function productionRunMaterials()
    {
        return $this->hasMany(\App\Models\ProductionRunMaterial::class);
    }

    /**
     * Hitung rata-rata penggunaan harian (7 hari terakhir)
     */
    public function rataRataHarian()
    {
        $avg = $this->dailyUsages()
            ->where('tanggal', '>=', now()->subDays(7))
            ->avg('qty_penggunaan');
        return $avg ?? 0;
    }

    /**
     * Hitung berapa hari stok akan habis
     */
    public function estimasiHariHabis()
    {
        $avg = $this->rataRataHarian();
        if ($avg <= 0) return null;
        return round($this->stok / $avg);
    }

    /**
     * Cek apakah stok menipis (stok <= stok_minimum tapi > 0)
     */
    public function isMenipis()
    {
        return $this->stok > 0 && $this->stok <= $this->stok_minimum;
    }

    /**
     * Cek apakah stok habis
     */
    public function isHabis()
    {
        return $this->stok == 0;
    }

    /**
     * Auto update status berdasarkan stok
     */
    public function updateStatusOtomatis()
    {
        if ($this->stok == 0) {
            $this->status = 'habis';
        } elseif ($this->stok <= $this->stok_minimum) {
            $this->status = 'menipis';
        } else {
            $this->status = 'tersedia';
        }
        return $this;
    }
}