<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Laptop ASUS ROG',
                'kategori' => 'Elektronik',
                'stok' => 15,
                'satuan' => 'unit',
                'harga_beli' => 15000000,
                'harga_jual' => 18000000,
                'lokasi' => 'Gudang A - Rak 1',
                'status' => 'tersedia',
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Mouse Wireless Logitech',
                'kategori' => 'Aksesoris',
                'stok' => 50,
                'satuan' => 'pcs',
                'harga_beli' => 150000,
                'harga_jual' => 250000,
                'lokasi' => 'Gudang B - Rak 3',
                'status' => 'tersedia',
            ],
            [
                'kode_barang' => 'BRG003',
                'nama_barang' => 'Keyboard Mechanical',
                'kategori' => 'Aksesoris',
                'stok' => 8,
                'satuan' => 'pcs',
                'harga_beli' => 500000,
                'harga_jual' => 750000,
                'lokasi' => 'Gudang B - Rak 2',
                'status' => 'tersedia',
            ],
            [
                'kode_barang' => 'BRG004',
                'nama_barang' => 'Monitor LG 24 inch',
                'kategori' => 'Elektronik',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 2000000,
                'harga_jual' => 2800000,
                'lokasi' => 'Gudang A - Rak 2',
                'status' => 'habis',
            ],
            [
                'kode_barang' => 'BRG005',
                'nama_barang' => 'Kabel HDMI 2m',
                'kategori' => 'Kabel',
                'stok' => 5,
                'satuan' => 'pcs',
                'harga_beli' => 35000,
                'harga_jual' => 65000,
                'lokasi' => 'Gudang C - Rak 1',
                'status' => 'tersedia',
            ],
            [
                'kode_barang' => 'BRG006',
                'nama_barang' => 'Webcam HD 1080p',
                'kategori' => 'Aksesoris',
                'stok' => 3,
                'satuan' => 'unit',
                'harga_beli' => 400000,
                'harga_jual' => 600000,
                'lokasi' => 'Gudang B - Rak 4',
                'status' => 'tersedia',
            ],
            [
                'kode_barang' => 'BRG007',
                'nama_barang' => 'Headset Gaming',
                'kategori' => 'Aksesoris',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 350000,
                'harga_jual' => 550000,
                'lokasi' => 'Gudang B - Rak 5',
                'status' => 'rusak',
            ],
            [
                'kode_barang' => 'BRG008',
                'nama_barang' => 'Printer Epson L3210',
                'kategori' => 'Elektronik',
                'stok' => 12,
                'satuan' => 'unit',
                'harga_beli' => 2500000,
                'harga_jual' => 3200000,
                'lokasi' => 'Gudang A - Rak 3',
                'status' => 'tersedia',
            ],
            [
                'kode_barang' => 'BRG009',
                'nama_barang' => 'Kertas A4 70gr',
                'kategori' => 'ATK',
                'stok' => 100,
                'satuan' => 'rim',
                'harga_beli' => 50000,
                'harga_jual' => 75000,
                'lokasi' => 'Gudang D - Rak 1',
                'status' => 'tersedia',
            ],
            [
                'kode_barang' => 'BRG010',
                'nama_barang' => 'Tinta Printer Black',
                'kategori' => 'ATK',
                'stok' => 25,
                'satuan' => 'botol',
                'harga_beli' => 85000,
                'harga_jual' => 120000,
                'lokasi' => 'Gudang D - Rak 2',
                'status' => 'tersedia',
            ],
        ];

        foreach ($data as $item) {
            Inventory::create($item);
        }
    }
}