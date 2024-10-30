<?php


namespace Context360\Model\Placements;


class PlacementList
{
	/**
	 * @var Placement[]
	 */
	protected $placements;

	/**
	 * PlacementList constructor.
	 */
	public function  __construct()
	{
		$this->placements = [];
	}

	public function addPlacement( Placement $placement )
	{
		$this->placements [] = $placement;
	}

	public function hasPlacements()
	{
		return count( $this->placements ) > 0;
	}

	/**
	 * @return Placement[]
	 */
	public function getPlacements()
	{
		return $this->placements;
	}

	public function setupDefaultPlacement()
	{
		usort($this->placements, function (Placement $a, Placement $b) {
			if ($a->getPlacementId() === $b->getPlacementId()) {
				return 0;
			}
			return ($a->getPlacementId() < $b->getPlacementId()) ? -1 : 1;
		});

		if (!empty($this->placements[0]) && $this->placements[0] instanceof Placement)
		{
			$this->placements[0]->setIsDefault(true);
		}
	}

	public function wasSuccessful()
	{
		return true;
	}
}