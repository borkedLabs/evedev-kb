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
    '/search'   => 'Search:index',
));

$app->config('debug', true);
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

\EDK\Core\EDK::init($app);
$app->run();


		