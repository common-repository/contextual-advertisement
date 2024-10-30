<?php


namespace Context360\Model\Placements;


use Context360\Model\AbstractValidatableModel;

class DisplayPlacementsRequest extends AbstractValidatableModel
{
	protected $placementsPositions;

	/**
	 * DisplayPlacementsRequest constructor.
	 *
	 * @param string $placementsPosition
	 */
	public function __construct($placementsPosition)
	{
		parent::__construct();
		$this->placementsPositions = $placementsPosition;
	}

	protected function validateFields()
	{
		if ( !(is_string($this->placementsPositions)) )
		{
			$this->addErrorMessage("Placements positions were not checked!");
		}
	}

	/**
	 * @return array
	 */
	public function getPlacementsPositions()
	{
		return explode(',', esc_attr($this->placementsPositions));
	}
}