<?php


namespace Context360\Service\Placements;


use Context360\Database\CredentialsRepository;
use Context360\Database\PlacementsRepository;
use Context360\Database\WebsiteRepository;
use Context360\Model\Placements\BadPlacementList;
use Context360\Model\Placements\PlacementList;
use Context360\Model\Placements\PlacementsFailureResponse;
use Context360\Service\OAuth2\OAuth2Service;

class PlacementsService
{
	protected $credentialsRepository;
	protected $placementsRepository;
	protected $websiteRepository;
	protected $placementsApiClient;
	protected $oAuth2Service;
	protected $publisherApi;

	/**
	 * PlacementsService constructor.
	 *
	 * @param CredentialsRepository $credentialsRepository
	 * @param PlacementsRepository $placementsRepository
	 * @param PlacementsApiClient $placementsApiClient
	 * @param WebsiteRepository $websiteRepository
	 * @param OAuth2Service $oAuth2Service
	 */
	public function __construct(
		CredentialsRepository $credentialsRepository,
		PlacementsRepository $placementsRepository,
		WebsiteRepository $websiteRepository,
		PlacementsApiClient $placementsApiClient,
		OAuth2Service $oAuth2Service
	)
	{
		$this->credentialsRepository = $credentialsRepository;
		$this->placementsRepository  = $placementsRepository;
		$this->websiteRepository     = $websiteRepository;
		$this->placementsApiClient   = $placementsApiClient;
		$this->oAuth2Service         = $oAuth2Service;
	}

	/**
	 * Get placements list - either from database or api.
	 * If not in database - try to download them from api and save to db.
	 *
	 * @return PlacementList
	 */
	public function getPlacements()
	{
		// Table must exist - created on plugin bootstrap (Activate plugin)

		$clientInformation = $this->credentialsRepository->getClientCredentials();
		$apiPublisherData  = $this->placementsApiClient->getPlacements($clientInformation, $this->oAuth2Service);
		if ($apiPublisherData instanceof PlacementsFailureResponse)
		{
			return new PlacementList();
		}
		$applicationId = $this->websiteRepository->getWebsite($clientInformation);
		$this->credentialsRepository->setPublisher($apiPublisherData->getPublisherId(), $clientInformation);

		// we must ignore this result to go on
		// simple reason - only first time we enter this page sets header code
		// any other time - it returns int(0) - because nothing is updated
		$this->websiteRepository->setHeaderCode($apiPublisherData, $applicationId);

		$website = $this->websiteRepository->getWebsite($clientInformation);
		if ( !$website)
		{
			return new BadPlacementList();
		}
		$placementsList = $this->placementsRepository->getPlacements();

		if ($placementsList->hasPlacements())
		{
			$this->placementsRepository->removeAllPlacements();
		}
		foreach ($apiPublisherData->getPlacementList()->getPlacements() as $placement)
		{
			$dbPlacementsResult = $this->placementsRepository->addPlacement($placement, $website);

			if ( !$dbPlacementsResult)
			{
				return new BadPlacementList();
			}
		}

		$placementsList = $this->placementsRepository->getPlacements();

		return $placementsList;
	}

	public function getPlacementLocation()
	{
		$clientInformation = $this->credentialsRepository->getClientCredentials();
		$currentWebsite    = $this->websiteRepository->getWebsite($clientInformation);
		$placementLocation = $this->websiteRepository->getWebsiteWithLocations($currentWebsite);

		return $placementLocation;
	}
}