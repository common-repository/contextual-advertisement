<?php


namespace Context360\Service\Placements;


use Context360\Database\PlacementsRepository;
use Context360\Model\Placements\PlacementListRequest;

class ListPlacementService
{
	protected $placementsRepository;
	protected $placementsService;

	/**
	 * DisplayPlacementsService constructor.
	 *
	 * @param PlacementsRepository $placementsRepository
	 * @param PlacementsService $placementsService
	 */
	public function __construct(PlacementsRepository $placementsRepository, PlacementsService $placementsService)
	{
		$this->placementsRepository = $placementsRepository;
		$this->placementsService    = $placementsService;
	}

	public function checkedPlacementPosition(PlacementListRequest $placementListRequest)
	{
		$checkPosition = str_replace(' ', '', implode($placementListRequest->getPlacementsList()));

		return $checkPosition;
	}


}