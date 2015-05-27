<?php

require __DIR__ . '/bootstrap/edk.php';

// Party time!
//include(__DIR__.'/common/index.php');

$app = New \SlimController\Slim(array(
    'templates.path'             => APP_PATH . '/templates',
    'controller.class_prefix'    => '\\EDK\\Controller',
    'controller.method_suffix'   => 'Action',
    'controller.template_suffix' => 'php',
));

$app->addRoutes(array(
    '/'            => 'Home:index',
    '/home'            => 'Home:index',
    '/home/:view/:year/:month/'            => 'Home:index',
    '/home/:year/:month/'            => 'Home:index',
    '/kill_detail/:id/'   => 'KillDetail:index',
    '/kill_related/:id/'   => 'KillRelated:index',
    '/pilot_detail/:id/'   => 'Pilot:detail',
    '/pilot_detail/:id/:view/'   => 'Pilot:detail',
    '/pilot/external/:id/'   => 'Pilot:external',
    '/pilot/external/:id/:view/'   => 'Pilot:external',
    '/alliance_detail/:id/'   => 'Alliance:detail',
    '/corp_detail/:id/'   => 'Corp:detail',
    '/corp_detail/:id/:view/'   => 'Corp:detail',
    '/system_detail/:sys_id/'   => 'System:detail',
    '/cc_detail/:id/'   => 'Contract:detail',
    '/invtype/:id/'   => 'InvType:index',
    '/awards'   => 'Awards:index',
    '/about'   => 'About:index',
    '/campaigns'   => 'Campaigns:index',
    '/campaigns/:view/'   => 'Campaigns:index',
    '/standings'   => 'Standings:index',
    '/search'   => 'Search:index',
    '/post'   => 'Post:index',
    '/self_detail'   => 'SelfDetail:index',
	
	'/login'	=> 'Login:index',
    '/admin'   => 'Admin\Home:index',
    '/admin/'   => 'Admin\Home:index',
    '/admin_audit/'   => 'Admin\Audit:index',
    '/admin_troubleshooting/'   => 'Admin\Troubleshooting:index',
    '/admin_cc/'   => 'Admin\Campaigns:index',
    '/admin_roles/'   => 'Admin\Roles:index',
    '/admin_standings/'   => 'Admin\Standings:index',
    '/admin_api/'   => 'Admin\API:index',
    '/admin_mapoptions/'   => 'Admin\Map:options',
    '/admin_navmanager/'   => 'Admin\Navigation:index',
    '/admin_verify/'   => 'Admin\Verify:index',
    '/admin_mods/'   => 'Admin\Mods:index',
    '/admin_status/'   => 'Admin\Status:index',
	'/admin_value_fetch/' => 'Admin\Fetch:values',
	'/admin_kill_import/' => 'Admin\Import:import',
	'/admin_kill_import_csv/' => 'Admin\Import:csv',
	'/admin_kill_export/' => 'Admin\Export:export',
	'/admin_zkbfetch/'  => 'Admin\Fetch:zkb',
	'/admin_idfeedsyndication/'  => 'Admin\Fetch:idfeed',
));

$app->config('debug', true);
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

\EDK\Core\EDK::init($app);
$app->run();


		