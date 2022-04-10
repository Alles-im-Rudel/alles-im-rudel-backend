<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BranchMemberShipRejectMail extends Mailable
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
	 * @return \App\Mail\BranchMemberShipRejectMail
	 */
	public function build(): BranchMemberShipRejectMail
	{

		return $this->subject('Vereins Spartenaustritsbestätigung')
			->view('emails.branchMemberShipRejectMail');
	}
}
