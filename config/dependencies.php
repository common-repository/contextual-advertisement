<?php

namespace Context360;

use Context360\Container\Container;
use Context360\Controller\Rest\PlacementLocationsRestController;
use Context360\Controller\Rest\PlacementsRestController;
use Context360\Controller\Rest\RegistrationRestController;
use Context360\Controller\Rest\StatisticsRestController;
use Context360\Controller\View\DashViewController;
use Context360\Controller\View\PlacementsViewController;
use Context360\Controller\View\StatisticsViewController;
use Context360\Database\CredentialsRepository;
use Context360\Database\PlacementsRepository;
use Context360\Database\WebsiteRepository;
use Context360\Service\OAuth2\OAuth2Service;
use Context360\Service\Placements\DisplayPlacementsService;
use Context360\Service\Placements\HeaderCodeApiService;
use Context360\Service\Placements\ListPlacementService;
use Context360\Service\Placements\PlacementsService;
use Context360\Service\Registration\CategoriesApiClient;
use Context360\Service\Registration\CategoriesService;
use Context360\Service\Registration\IpProvider;
use Context360\Service\Placements\PlacementsApiClient;
use Context360\Service\Registration\RegistrationService;
use Context360\Service\Registration\CredentialsApiClient;
use Context360\Service\OAuth2\OAuth2ApiClient;
use Context360\Service\Statistics\StatisticsApiClient;
use Context360\Service\Statistics\StatisticsService;


require __DIR__ . '/config.php';

/** @var Container $container */
$container->set('views', function () {
	return require __DIR__ . '/templates.php';
});


$container->set("Context360\\Controller\\View\\DashViewController", function (Container $container) {
	$views              = $container->get('views');
	$credentialsService = $container->get('Context360\\Service\\OAuth2\\OAuth2Service');
	$categoriesService  = $container->get('Context360\\Service\\Registration\\CategoriesService');

	return new DashViewController(
		$views,
		$credentialsService,
		$categoriesService,
		$container->get('config.plugin.api.recaptcha.token'),
		$container->get('config.plugin.api.recaptcha.url'),
		$container->get('config.plugin.api.version')
	);
});

$container->set("Context360\\Controller\\View\\PlacementsViewController", function (Container $container) {
	$views              = $container->get('views');
	$credentialsService = $container->get('Context360\\Service\\OAuth2\\OAuth2Service');
	$categoriesService  = $container->get('Context360\\Service\\Registration\\CategoriesService');
	$placementsService  = $container->get('Context360\\Service\\Placements\\PlacementsService');

	return new PlacementsViewController(
		$views,
		$credentialsService,
		$categoriesService,
		$placementsService,
		$container->get('config.plugin.api.version'));
});
$container->set("Context360\\Controller\\View\\StatisticsViewController", function (Container $container) {
	$views              = $container->get('views');
	$credentialsService = $container->get('Context360\\Service\\OAuth2\\OAuth2Service');
	$categoriesService  = $container->get('Context360\\Service\\Registration\\CategoriesService');
	$placementsService  = $container->get('Context360\\Service\\Placements\\PlacementsService');
	$statisticsService  = $container->get('Context360\\Service\\Statistics\\StatisticsService');

	return new StatisticsViewController(
		$views,
		$credentialsService,
		$categoriesService,
		$placementsService,
		$statisticsService,
		$container->get('config.plugin.api.version'));
});


$container->set('Context360\\Service\\Registration\\CategoriesApiClient', function ($container) {
	$apiHost = $container->get('config.plugin.api.host');

	return new CategoriesApiClient($apiHost);
});
$container->set('Context360\\Service\\Registration\\CategoriesService', function (Container $container) {
	return new CategoriesService(
		$container->get('Context360\\Service\\Registration\\CategoriesApiClient')
	);
});
$container->set('Context360\\Service\\Registration\\CategoriesService', function (Container $container) {
	return new CategoriesService(
		$container->get('Context360\\Service\\Registration\\CategoriesApiClient')
	);
});
$container->set('Context360\\Service\\Placements\\HeaderCodeApiService', function (Container $container) {
	return new HeaderCodeApiService(
		$container->get('Context360\\Database\\CredentialsRepository'),
		$container->get('Context360\\Database\\WebsiteRepository')
	);
});

$container->set('Context360\\Service\\Registration\\CredentialsApiClient', function (Container $container) {
	$apiHost = $container->get('config.plugin.api.host');

	return new CredentialsApiClient($apiHost);
});
$container->set('Context360\\Service\\Registration\\IpProvider', function ($container) {
	return new IpProvider();
});

$container->set('Context360\\Service\\Registration\\RegistrationService', function (Container $container) {
	return new RegistrationService(
		$container->get('Context360\\Database\\CredentialsRepository'),
		$container->get('Context360\\Database\\WebsiteRepository'),
		$container->get('Context360\\Service\\Registration\\CredentialsApiClient'),
		$container->get('Context360\\Service\\Registration\\IpProvider')
	);
});


$container->set('Context360\\Service\\OAuth2\\OAuth2ApiClient', function (Container $container) {
	$apiHost = $container->get('config.plugin.api.host');

	return new OAuth2ApiClient($apiHost);
});


$container->set('Context360\\Service\\OAuth2\\OAuth2Service', function (Container $container) {
	return new OAuth2Service(
		$container->get('Context360\\Database\\CredentialsRepository'),
		$container->get('Context360\\Service\\OAuth2\\OAuth2ApiClient')
	);
});


$container->set('Context360\\Controller\\Rest\\RegistrationRestController', function (Container $container) {
	return new RegistrationRestController(
		$container->get('Context360\\Service\\Registration\\RegistrationService')
	);
});

$container->set('Context360\\Controller\\Rest\\StatisticsRestController', function (Container $container) {
	return new StatisticsRestController(
		$container->get('Context360\\Service\\Statistics\\StatisticsService')
	);
});

$container->set('Context360\\Service\\Placements\\DisplayPlacementsService', function (Container $container) {
	return new DisplayPlacementsService(
		$container->get('Context360\\Database\\WebsiteRepository'),
		$container->get('Context360\\Database\\CredentialsRepository')
	);
});

$container->set('Context360\\Controller\\Rest\\PlacementLocationsRestController', function (Container $container) {
	return new PlacementLocationsRestController(
		$container->get('Context360\\Service\\Placements\\DisplayPlacementsService')
	);
});
$container->set('Context360\\Controller\\Rest\\PlacementsRestController', function (Container $container) {
	return new PlacementsRestController(
		$container->get('Context360\\Service\\Placements\\ListPlacementService'),
		$container->get('Context360\\Service\\Registration\\RegistrationService')
	);
});
$container->set('Context360\\Service\\Placements\\ListPlacementService', function (Container $container) {
	return new ListPlacementService(
		$container->get('Context360\\Database\\PlacementsRepository'),
		$container->get('Context360\\Service\\Placements\\PlacementsService')
	);
});


$container->set('Context360\\Service\\Placements\\PlacementsApiClient', function (Container $container) {
	$apiHost = $container->get('config.plugin.api.host');

	return new PlacementsApiClient($apiHost);
});

$container->set('Context360\\Service\\Placements\\PlacementsService', function (Container $container) {
	return new PlacementsService(
		$container->get('Context360\\Database\\CredentialsRepository'),
		$container->get('Context360\\Database\\PlacementsRepository'),
		$container->get('Context360\\Database\\WebsiteRepository'),
		$container->get('Context360\\Service\\Placements\\PlacementsApiClient'),
		$OAuth2TokenService = $container->get('Context360\\Service\\OAuth2\\OAuth2Service')
	);
});


$container->set('Context360\\Service\\Statistics\\StatisticsApiClient', function (Container $container) {
	$apiHost = $container->get('config.plugin.api.host');

	return new StatisticsApiClient($apiHost);
});

$container->set('Context360\\Service\\Statistics\\StatisticsService', function (Container $container) {
	return new StatisticsService(
		$container->get('Context360\\Database\\CredentialsRepository'),
		$OAuth2TokenService = $container->get('Context360\\Service\\OAuth2\\OAuth2Service'),
		$container->get('Context360\\Service\\Statistics\\StatisticsApiClient')
	);
});


$container->set('Context360\\Database\\CredentialsRepository', function (Container $container) {
	global $wpdb;

	return new CredentialsRepository($wpdb);
});
$container->set('Context360\\Database\\WebsiteRepository', function (Container $container) {
	global $wpdb;

	return new WebsiteRepository($wpdb);
});
$container->set('Context360\\Database\\PlacementsRepository', function (Container $container) {
	global $wpdb;

	return new PlacementsRepository($wpdb);
});