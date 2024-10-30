<?php

/*
@link              https://www.context360.net
@since             1.2.0
@package           Context360

@wordpress-plugin
Plugin Name: Contextual advertisement
Plugin URI: https://www.context360.net
Description: This plugin allows context360 advertisement platform to manage contextual text links on wordpress website.
Version: 1.2.6
Author: https://www.mc2.pl
License: LGPL v3.0
License URL: http://www.gnu.org/licenses/lgpl-3.0.html
Since: 1.2.0
Text Domain: ad-context
Domain Path:       /languages
*/

require_once __DIR__ . '/Autoloader.php';

use Context360\Assets\Loaders\AdminAssetsLoader;
use Context360\Autoloader;
use Context360\Container\Container;
use Context360\Controller\Rest\PlacementsRestController;
use Context360\Controller\Rest\RegistrationRestController;
use Context360\Controller\Rest\PlacementLocationsRestController;
use Context360\Controller\Rest\StatisticsRestController;
use Context360\Controller\View\DashViewController;
use Context360\Controller\View\PlacementsViewController;
use Context360\Controller\View\StatisticsViewController;
use Context360\Database\CredentialsRepository;
use Context360\Database\PlacementsRepository;
use Context360\Database\WebsiteRepository;
use Context360\Service\Placements\HeaderCodeApiService;
use Context360\Service\Placements\PlacementsService;
use Context360\Service\Placements\DisplayPlacementsService;

try
{
	Autoloader::register();
	// autoloader may throw exception, so handle it

	$adminLoader = new AdminAssetsLoader();
	$adminLoader->init();

	$container = new Container();

	include __DIR__ . '/config/dependencies.php';

	// on plugin activation event create DB structures
	register_activation_hook(__FILE__, function () use ($container) {

		/** @var CredentialsRepository $credentialsRepository */
		$credentialsRepository = $container->get('Context360\\Database\\CredentialsRepository');
		$credentialsRepository->createTable();
		/** @var WebsiteRepository $websiteRepository */
		$websiteRepository = $container->get('Context360\\Database\\WebsiteRepository');
		$websiteRepository->createTable();
		/** @var PlacementsRepository $placementRepository */
		$placementRepository = $container->get('Context360\\Database\\PlacementsRepository');
		$placementRepository->createTable();
	});

	add_action('admin_menu', function () use ($container) {

		add_menu_page(
			'Context360', 'Context360', 'manage_options',
			'context360', '', '', 75
		);

		add_submenu_page(
			'context360', 'Ustawienia', 'Ustawienia',
			'manage_options', 'my-submenu-ustawienia',
			function () use ($container) {

				/** @var DashViewController $dashViewController */
				$dashViewController = $container->get("Context360\\Controller\\View\\DashViewController");
				$dashViewController->renderTemplate();
			}
		);
		add_submenu_page(
			'context360', 'Zaawansowane', 'Zaawansowane',
			'manage_options', 'my-submenu-zaawansowane',
			function () use ($container) {

				/** @var PlacementsViewController $placementsViewController */
				$placementsViewController = $container->get("Context360\\Controller\\View\\PlacementsViewController");
				$placementsViewController->renderTemplate();
			}
		);
		add_submenu_page(
			'context360', 'Statystyki', 'Statystyki',
			'manage_options', 'my-submenu-statystyki',
			function () use ($container) {

				/** @var StatisticsViewController $statisticsViewController */
				$statisticsViewController = $container->get("Context360\\Controller\\View\\StatisticsViewController");
				$statisticsViewController->renderTemplate();
			}
		);
		remove_submenu_page('context360', 'context360');

	});

	/**
	 * Here we inject init section (which is a string containing HTML source code) on user's page.
	 */
	add_action('wp_head', function () use ($container) {
		/** @var HeaderCodeApiService $websiteHeaderCode */
		$websiteHeaderCode = $container->get('Context360\\Service\\Placements\\HeaderCodeApiService');
		echo $websiteHeaderCode->getWebsiteWithHeaderCode()->getHeaderCode();
	});


	add_action("wp_ajax_context360_register", function () use ($container) {

		/** @var RegistrationRestController $settingsRestController */
		$settingsRestController = $container->get('Context360\\Controller\\Rest\\RegistrationRestController');
		$settingsRestController->handleRequest(array_merge([], $_POST));
	});
	add_action("wp_ajax_context360_placements_position", function () use ($container) {
		/** @var PlacementLocationsRestController $displayPlacementsController */
		$displayPlacementsController = $container->get('Context360\\Controller\\Rest\\PlacementLocationsRestController');
		$displayPlacementsController->handleRequest(array_merge([], $_POST));
	});
	add_action("wp_ajax_context360_placements_list", function () use ($container) {
		/** @var PlacementsRestController $placementsListController */
		$placementsListController = $container->get('Context360\\Controller\\Rest\\PlacementsRestController');
		$placementsListController->handleRequest(array_merge([], $_POST));
	});
	add_action("wp_ajax_context360_statistics_data", function () use ($container) {
		/** @var StatisticsRestController $statisticsRestController */
		$statisticsRestController = $container->get('Context360\\Controller\\Rest\\StatisticsRestController');
		$statisticsRestController->handleRequest(array_merge([], $_POST));
	});

	add_action("wp_ajax_context360_deactivate", function () use ($container) {
		deactivate_plugins('/context360/context360.php');
		wp_redirect(admin_url('/plugins.php'));
	});

	/** @var DisplayPlacementsService $displayPlacementsService */
	$displayPlacementsService = $container->get('Context360\\Service\\Placements\\DisplayPlacementsService');
	$checkedPositions         = explode(',', $displayPlacementsService->getCurrentWebsite()->getDisplayPositions());
	function addPlacementsToCurrentPage(Container $container)
	{
		add_filter("the_content", function ($content) use ($container) {

			/** @var PlacementsService $placementService */
			$placementService = $container->get('Context360\\Service\\Placements\\PlacementsService');
			foreach ($placementService->getPlacements()->getPlacements() as $placement)
			{
				if ($placement->getPlacementId())
				{
					$content .= $placement->getHtmlCode();
				}
			};

			return $content;
		});
	}

	/**
	 * Test if option was checked.
	 *
	 * @param $placementOption
	 * @param $positions
	 *
	 * @return bool
	 */
	function optionWasChecked($placementOption, $positions)
	{
		return in_array($placementOption, $positions);
	}

	/**
	 * Here we inject ad (which is a string containing HTML source code) on user's page.
	 */
	add_action('the_post', function () use ($checkedPositions, $container) {
		$validCasePost       = optionWasChecked(1, $checkedPositions) && !is_page() && !is_category() && !is_front_page();
		$validCasePage       = optionWasChecked(2, $checkedPositions) && is_page();
		$validCaseCategories = optionWasChecked(3, $checkedPositions) && is_category();
		$validCaseFrontPage  = optionWasChecked(4, $checkedPositions) && is_front_page();

		$shouldAddPlacementsToCurrentPage = $validCasePost ||
		                                    $validCaseFrontPage ||
		                                    $validCasePage ||
		                                    $validCaseCategories;

		if ($shouldAddPlacementsToCurrentPage)
		{
			addPlacementsToCurrentPage($container);
		}
	});
} catch (\Exception $exception)
{
	// do nothing
}