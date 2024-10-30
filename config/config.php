<?php

/** @var Container $container */
use Context360\Container\Container;

$container->set( 'config.plugin.api.host', function () {
	return 'https://api.contexter.net';
} );

$container->set( 'config.plugin.api.recaptcha.token', function () {
	return '6Ld2Z6QUAAAAAMADoAFI6jhBgpsTbeDwCFPXsFmU';
} );

$container->set( 'config.plugin.api.version', function () {
	return '1.2.6';
} );

$container->set( 'config.plugin.api.recaptcha.url', function () {
	return 'https://www.recaptcha.net/recaptcha/api.js';
} );