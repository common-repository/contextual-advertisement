<?php


namespace Context360\Model\Placements;


use Context360\Model\AbstractValidatableModel;

class PlacementListRequest extends AbstractValidatableModel
{
	protected $placementsList;

	/**
	 * DisplayPlacementsRequest constructor.
	 *
	 * @param $placementsList
	 */
	public function __construct($placementsList)
	{
		parent::__construct();
		$this->placementsList = $placementsList;
	}

	protected function validateFields()
	{
		if ( !(is_string($this->placementsList)) || empty($this->placementsList))
		{
			$this->addErrorMessage("Placements list was not checked!");
		}
	}

	/**
	 * @return array
	 */
	public function getPlacementsList()
	{
		return explode(',', esc_attr($this->placementsList));
	}
}