<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Material;
use App\Mail\StokMenipisWarning;
use Illuminate\Support\Facades\Mail;

class CekStokMenipis extends Command
{
    protected $signature = 'inventory:cek-stok';
    protected $description = 'Cek stok material yang menipis dan kirim email warning';

    public function handle()
    {
        $materialsMenipis = Material::where('stok', '<=', \DB::raw('stok_minimum'))
            ->where('status', '!=', 'habis')
            ->get();

        if ($materialsMenipis->count() > 0) {
            // Kirim email ke admin
            Mail::to('admin@atmi.com')->send(new StokMenipisWarning($materialsMenipis));
            $this->info('Email warning terkirim untuk ' . $materialsMenipis->count() . ' material.');
        } else {
            $this->info('Semua stok material aman.');
        }

        return 0;
    }
}