<?php

namespace App\Mail;

use App\Models\Material;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StokMenipisWarning extends Mailable
{
    use Queueable, SerializesModels;

    public $materials;

    public function __construct($materials)
    {
        $this->materials = $materials;
    }

    public function build()
    {
        return $this->subject('⚠️ Warning: Stok Material Menipis - ATMI Inventory')
                    ->view('emails.stok-menipis');
    }
}