<?php namespace Config;

use App\Filters\ApiAuth;
use App\Filters\StaffAuth;
use App\Filters\UserAuth;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => CSRF::class,
		'toolbar'  => DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
        'userAuth' => UserAuth::class,
        'staffAuth' => StaffAuth::class,
        'apiAuth' => ApiAuth::class
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			//'honeypot'
			'csrf' => [
			    'except' => [
			        'api/[a-z]+/[a-z]+',
                    'api/[a-z]+/[a-z]+/[0-9]+',
                ]
            ],
		],
		'after'  => [
			//'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [];
}
