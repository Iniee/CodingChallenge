<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
     use Queueable, SerializesModels;

    public $ingredientName;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ingredientName, $message)
    {
        $this->ingredientName = $ingredientName;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ingredient below 50%')
            ->view('emails.stock_message');
    }
}