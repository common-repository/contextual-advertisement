<?php


namespace Context360\Model\Placements;


class Publisher
{
	protected $placements;
	protected $publisherId;
	protected $headerCode;

	/**
	 * SuccessfulApiPlacements constructor.
	 *
	 * @param array $apiPublisher
	 */
	public function __construct($apiPublisher = [])
	{
		$this->publisherId = $apiPublisher['publisherId'];
		$list              = new PlacementList();
		$this->headerCode  = $apiPublisher['headerCode'];

		foreach ($apiPublisher['placements'] as $apiPlacement)
		{
			$list->addPlacement(new Placement(
				$apiPlacement['placement_id'],
				$apiPlacement['name'],
				$apiPlacement['html_code']
			));
		}
		$list->setupDefaultPlacement();

		$this->placements = $list;
	}

	/**
	 * @return int
	 */
	public function getPublisherId()
	{
		return intval($this->publisherId);
	}

	/**
	 * @return string
	 */
	public function getHeaderCode()
	{
		return $this->headerCode;
	}

	/**
	 * @return PlacementList
	 */
	public function getPlacementList()
	{
		return $this->placements;
	}
}