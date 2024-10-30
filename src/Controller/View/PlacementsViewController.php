<?php


namespace Context360\Controller\View;

use Context360\Controller\AbstractViewController;
use Context360\Model\View\PlacementsView;
use Context360\Model\View\ViewModel;
use Context360\Service\OAuth2\OAuth2Service;
use Context360\Service\Placements\PlacementsService;
use Context360\Service\Registration\CategoriesService;

class PlacementsViewController extends AbstractViewController
{
	protected $views;
	protected $credentialsService;
	protected $categoriesService;
	protected $placementsService;

	/**
	 * DashViewController constructor.
	 *
	 * @param array $views - list of views from templates.php
	 * @param OAuth2Service $oAuth2Service
	 * @param CategoriesService $categoriesService
	 * @param PlacementsService $placementsService
	 * @param $pluginVersion
	 */
	public function __construct(
		$views,
		OAuth2Service $oAuth2Service,
		CategoriesService $categoriesService,
		PlacementsService $placementsService,
		$pluginVersion
	)
	{
		$this->views              = $views;
		$this->credentialsService = $oAuth2Service;
		$this->categoriesService  = $categoriesService;
		$this->placementsService  = $placementsService;
		$this->setVersion($pluginVersion);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function render()
	{
		$clientCredentials = $this->credentialsService->getCredentials();

		if ( !$clientCredentials->hasCredentials())
		{
			$categories = $this->categoriesService->getCategories();

			return new ViewModel($this->views['permission'], $categories);
		}
		if ( !$clientCredentials->hasAnyToken())
		{
			return new ViewModel($this->views['email'], []);
		}

		if ($clientCredentials->hasValidToken() && $clientCredentials->hasCredentials())
		{
			$placementsView = new PlacementsView();
			$placements     = $this->placementsService->getPlacements();
			$placementsView->setPlacements($placements);
			$placementsLocation = $this->placementsService->getPlacementLocation();
			$placementsView->setWebsite($placementsLocation);

			return new ViewModel($this->views['placements'], $placementsView);
		}

		// necessary?
		$categories = $this->categoriesService->getCategories();

		return new ViewModel($this->views['permission'], $categories);
	}


	public function addScripts()
	{
		$this->addPluginScript('/assets/js/placements.js');
	}
}