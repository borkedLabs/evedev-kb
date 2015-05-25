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
    '/pilot_detail/:id/'   => 'PilotDetail:index',
    '/alliance_detail/:id/'   => 'AllianceDetail:index',
    '/corp_detail/:id/'   => 'CorpDetail:index',
    '/corp_detail/:id/:view/'   => 'CorpDetail:index',
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


		