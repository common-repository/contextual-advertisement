<?php


namespace Context360\Model\Placements;


class Placement
{
	protected $htmlCode;
	protected $placementId;
	protected $placementName;
	protected $isDefault;

	/**
	 * Placement constructor.
	 *
	 * @param $placementId
	 * @param $htmlCode
	 * @param bool $isDefault
	 * @param $placementName
	 */
	public function __construct($placementId, $placementName, $htmlCode, $isDefault = false)
	{
		$this->placementId   = $placementId;
		$this->placementName = $placementName;
		$this->htmlCode      = $htmlCode;
		$this->isDefault     = (bool)$isDefault;
		$this->isDefault     = $isDefault;
	}

	/**
	 * @return string
	 */
	public function getHtmlCode()
	{
		return $this->htmlCode;
	}

	/**
	 * @return int
	 */
	public function getPlacementId()
	{
		return esc_attr($this->placementId);
	}

	/**
	 * @return string
	 */
	public function getPlacementName()
	{
		return esc_attr($this->placementName);
	}

	/**
	 * @return bool
	 */
	public function getIsDefault()
	{
		return esc_attr($this->isDefault);
	}

	/**
	 * @param bool $isDefault
	 */
	public function setIsDefault($isDefault)
	{
		$this->isDefault = esc_attr($isDefault);
	}
}