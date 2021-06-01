<?php 

return [
	'dashboard_url' => '/admin',

	'footer' => [
		'show_copyright' => false,
		'copyright_text' => 'COPYRIGHT &copy; '. date('Y') .' <a class="text-bold-800 grey darken-2" href="" target="_blank">Topdot,</a> All rights Reserved'
	],
	
	'scripts' => [
		'js/app.js',
	],

	'sidebar' => [
		'main_heading' => 'Laravel Admin',
	],

	'stylesheets' => [
		'css/app.css',
	],

	'routes' => [
		'dashboard' => 'laravel-admin.dashboard',

		'profile' => [
			'edit' => 'laravel-admin.profile.edit'
		],
		'user' => [
			'login' => 'laravel-admin.login',
			'logout' => 'laravel-admin.logout',
		],
	],

	'router' => [
		'middleware_web' => 'web',
		'prefix' => 'admin',
	],
];