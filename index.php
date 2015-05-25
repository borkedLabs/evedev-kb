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
    '/kill_detail/:id/'   => 'KillDetail:index',
    '/pilot_detail/:id/'   => 'Pilot:detail',
    '/alliance_detail/:id/'   => 'Alliance:detail',
    '/corp_detail/:id/'   => 'Corp:detail',
    '/corp_detail/:id/:view/'   => 'Corp:detail',
    '/system_detail/:sys_id/'   => 'System:detail',
    '/cc_detail/:id/'   => 'ContractDetail:index',
    '/awards'   => 'Awards:index',
    '/about'   => 'About:index',
    '/campaigns'   => 'Campaigns:index',
    '/search'   => 'Search:index',
));

$app->config('debug', true);
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

\EDK\Core\EDK::init($app);
$app->run();


		