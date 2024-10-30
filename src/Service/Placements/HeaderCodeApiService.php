<?php


namespace Context360\Service\Placements;


use Context360\Database\CredentialsRepository;
use Context360\Database\WebsiteRepository;
use Context360\Model\Placements\Website;

class HeaderCodeApiService
{
	protected $credentialsRepository;
	protected $websiteRepository;

	/**
	 * HeaderCodeApiService constructor.
	 *
	 * @param CredentialsRepository $credentialsRepository
	 * @param WebsiteRepository $websiteRepository
	 */
	public function __construct(CredentialsRepository $credentialsRepository, WebsiteRepository $websiteRepository)
	{
		$this->websiteRepository = $websiteRepository;
		$this->credentialsRepository = $credentialsRepository;
	}

	/**
	 * @return Website
	 */
	public function getWebsiteWithHeaderCode()
	{
		$credentials = $this->credentialsRepository->getClientCredentials();
		$website = $this->websiteRepository->getWebsite($credentials);
		$websiteWithHeaderCode = $this->websiteRepository->getUsersWebsite($website);

		return $websiteWithHeaderCode;
	}

}