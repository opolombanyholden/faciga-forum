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
     *
     * @param Company $company
     * @param string $rejectionReason
     */
    public function __construct(Company $company, string $rejectionReason)
    {
        $this->company = $company;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('FACIGA 2025 - Statut de votre candidature')
                    ->view('emails.company-rejected')
                    ->with([
                        'company' => $this->company,
                        'rejectionReason' => $this->rejectionReason,
                    ]);
    }
}