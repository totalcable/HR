<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordChange extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     *
     */

    protected $emp;
    protected $newPass;

    public function __construct($emp,$newPass)
    {
        $this->emp = $emp;
        $this->newPass = $newPass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Email.PasswordChanged')
            ->with([
                'emp' => $this->emp,
                'newPass' => $this->newPass,

            ]);
    }
}
