<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailSubject;
    public $replyMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $message)
    {
        $this->emailSubject = $subject;
        $this->replyMessage = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->emailSubject)
            ->view('emails.contact-reply')
            ->with([
                'subject' => $this->emailSubject,
                'replyMessage' => $this->replyMessage,
            ]);
    }
}
