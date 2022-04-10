<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemberShipRejectMail extends Mailable
{
	use Queueable, SerializesModels;

	public $user;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($user)
	{
		$this->user = $user;
	}

	/**
	 * Build the message.
	 *
	 * @return \App\Mail\MemberShipAcceptMail
	 */
	public function build(): MemberShipAcceptMail
	{

		return $this->subject('Verseins Beitritablehnung')
			->view('emails.memberShipRejectMail');
	}
}
