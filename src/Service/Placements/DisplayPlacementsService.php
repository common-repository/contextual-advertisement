<?php


namespace Context360\Service\Placements;


use Context360\Database\CredentialsRepository;
use Context360\Database\WebsiteRepository;
use Context360\Model\Placements\DisplayPlacementsRequest;
use Context360\Model\Placements\DisplayPosition;
use Context360\Model\Placements\PlacementLocationUpdatedResponse;
use Context360\Model\Placements\PlacementsFailureResponse;
use Context360\Model\Placements\Website;

class DisplayPlacementsService
{
	protected $websiteRepository;
	protected $credentialsRepository;
	protected $displayPlacementsRequest;

	/**
	 * DisplayPlacementsService constructor.
	 *
	 * @param WebsiteRepository $websiteRepository
	 * @param CredentialsRepository $credentialsRepository
	 */
	public function __construct(WebsiteRepository $websiteRepository, CredentialsRepository $credentialsRepository)
	{
		$this->websiteRepository     = $websiteRepository;
		$this->credentialsRepository = $credentialsRepository;
	}

	/**
	 * @param DisplayPlacementsRequest $displayPlacementsRequest
	 *
	 * @return PlacementLocationUpdatedResponse|PlacementsFailureResponse
	 */
	public function savePlacementsPositions(DisplayPlacementsRequest $displayPlacementsRequest)
	{
		$displayList       = implode(',', $displayPlacementsRequest->getPlacementsPositions());
		$clientInformation = $this->credentialsRepository->getClientCredentials();
		$applicationId     = $this->websiteRepository->getWebsite($clientInformation);

		$wasUpdated          = $this->websiteRepository->setPlacementLocations($applicationId, new DisplayPosition($displayList));

		if ($wasUpdated === 0)
		{
			return new PlacementLocationUpdatedResponse(304, "Placement locations not modified");
		}

		if (!$wasUpdated)
		{
			return new PlacementsFailureResponse(500, "We could not save placement locations");
		}

		return new PlacementLocationUpdatedResponse();
	}

	/**
	 * @return Website
	 */

	public function getCurrentWebsite()
	{
		if ( !$this->websiteRepository->tableExists())
		{
			return new Website();
		}

		$currentWebsite = $this->websiteRepository->getWebsiteWithLocations(
			$this->websiteRepository->getWebsite(
				$this->credentialsRepository->getClientCredentials()));

		return $currentWebsite;
	}
}