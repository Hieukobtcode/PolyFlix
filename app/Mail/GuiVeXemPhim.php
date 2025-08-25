<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Str;
use App\Models\CauHinh;
use DNS1D;

class GuiVeXemPhim extends Mailable
{
    use Queueable, SerializesModels;

    public $datVe;
    public $pdf;
    public $barcode;
    public $barcodeUrl;
    public $cauHinh;


    public function __construct($datVe)
    {
        $this->datVe = $datVe;
        $this->cauHinh = CauHinh::first();
    }

    public function build()
    {
        // Tạo tên file ngẫu nhiên
        $filename = 'barcode_' . Str::random(6) . '.png';

        // Tạo ảnh barcode base64 → binary
        $barcodeBinary = base64_decode(DNS1D::getBarcodePNG($this->datVe->ma_dat_ve, 'C128', 2, 60));

        return $this->subject('🎟️ Vé xem phim từ PolyFlix')
            ->view('emails.ve')
            ->attachData($barcodeBinary, $filename, [
                'mime' => 'image/png',
            ])
            ->with([
                'barcodeCid' => $filename,
                'datVe' => $this->datVe,
                'cauHinh' => $this->cauHinh,
            ]);
    }
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ve_pdf',
        );
    }
}