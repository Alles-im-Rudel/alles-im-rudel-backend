<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BranchMemberShipAcceptMail extends Mailable
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
	 * @return \App\Mail\BranchMemberShipAcceptMail
	 */
	public function build(): BranchMemberShipAcceptMail
	{

		return $this->subject('Vereins Spartenbeitritsbestätigung')
			->view('emails.branchMemberShipAcceptMail');
	}
}
