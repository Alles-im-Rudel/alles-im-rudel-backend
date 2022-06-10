<?php

namespace App\Classes\Discord;

use Illuminate\Support\Facades\Http;

class DiscordMassege
{
	private string $webhook;
	private string $title;
	private string $message;

	public function __construct($title, $message)
	{
		$this->webhook = env('DISCORD_WEBHOOK');
		$this->title = $title;
		$this->message = $message;
	}

	/**
	 * @return void
	 */
	public function sendMessage(): void
	{
		Http::post($this->webhook,
			[
				'embeds' => [
					[
						'title'       => $this->title,
						'description' => $this->message,
						'color'       => '7506394',
					]
				],
			]);
	}
}
