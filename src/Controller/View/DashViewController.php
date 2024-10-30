<?php

namespace Context360\Controller\View;

use Context360\Controller\AbstractViewController;
use Context360\Service\OAuth2\OAuth2Service;
use Context360\Service\Registration\CategoriesService;
use Context360\Model\View\ViewModel;

class DashViewController extends AbstractViewController
{
	protected $views;
	protected $credentialsService;
	protected $categoriesService;
	protected $reCaptchaToken;
	protected $reCaptchaUrl;

	/**
	 * DashViewController constructor.
	 *
	 * @param array $views - list of views from templates.php
	 * @param OAuth2Service $oAuth2Service
	 * @param CategoriesService $categoriesService
	 * @param string $reCaptchaToken - reCaptcha token (public one) passed to api.js
	 * @param string $reCaptchaUrl
	 * @param string $pluginVersion - version to avoid/enable hitting cache
	 */
	public function __construct(
		$views,
		OAuth2Service $oAuth2Service,
		CategoriesService $categoriesService,
		$reCaptchaToken,
		$reCaptchaUrl,
		$pluginVersion
	)
	{
		$this->views              = $views;
		$this->credentialsService = $oAuth2Service;
		$this->categoriesService  = $categoriesService;
		$this->reCaptchaToken     = $reCaptchaToken;
		$this->reCaptchaUrl       = $reCaptchaUrl;
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

			return new ViewModel($this->views['registration'], $categories);
		}

		$categories = $this->categoriesService->getCategories();

		return new ViewModel($this->views['settings'], $categories);
	}

	public function addScripts()
	{
		$this->addPluginScript('/assets/js/reCaptchaToken.js');
		$this->addPluginScript('/assets/js/registration.js');
		$this->addPluginAssetScript(
			$this->reCaptchaUrl . '?render=' . $this->reCaptchaToken . '',
			false
		);
	}
}