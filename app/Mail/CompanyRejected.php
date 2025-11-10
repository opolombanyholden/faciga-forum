<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(Company $company, string $rejectionReason = null)
    {
        $this->company = $company;
        $this->rejectionReason = $rejectionReason ?? 'Votre dossier ne répond pas aux critères de participation.';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('FACIGA 2025 - Votre candidature')
                    ->view('emails.company-rejected');
    }
}