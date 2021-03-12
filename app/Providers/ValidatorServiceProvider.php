<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		$this->app['validator']->extend('commentable', function ($attribute, $value, $parameters) {
			return in_array($value, [Post::class, Comment::class], true);
		});
	}
}
