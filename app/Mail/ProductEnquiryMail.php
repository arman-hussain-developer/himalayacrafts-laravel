<?php

namespace App\Mail;

use App\Models\ProductEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProductEnquiry $enquiry)
    {
        $this->enquiry->loadMissing(['product', 'variant']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Product Enquiry: ' . ($this->enquiry->product_name ?: 'Catalog Enquiry'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enquiries.product',
        );
    }
}
