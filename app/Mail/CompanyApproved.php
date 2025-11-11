<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param  Company  $company
     * @param  string  $password  Le mot de passe en clair généré
     */
    public function __construct(Company $company, string $password)
    {
        $this->company = $company;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('✅ Dossier approuvé - FACIGA 2025 - Vos identifiants de connexion')
                    ->view('emails.company-approved');
    }
}