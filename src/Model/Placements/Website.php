<?php


namespace Context360\Model\Placements;


class Website
{
	protected $applicationId;
	protected $wpId;
	protected $wordpressBlogId;
	protected $displayPosition;
	protected $headerCode;

	/**
	 * Website constructor.
	 *
	 * @param string $applicationId
	 * @param string $wordpressUserId
	 * @param string $wordpressBlogId
	 * @param string $displayPosition
	 * @param string $headerCode
	 */
	public function __construct(
		$applicationId = '', $wordpressUserId = '',
		$wordpressBlogId = '', $displayPosition = null,
		$headerCode = ''
	)
	{
		$this->applicationId   = $applicationId;
		$this->wpId            = $wordpressUserId;
		$this->displayPosition = $displayPosition;
		$this->headerCode      = $headerCode;
		$this->wordpressBlogId = $wordpressBlogId;
	}

	/**
	 * @return string
	 */
	public function getApplicationId()
	{
		return esc_attr($this->applicationId);
	}

	/**
	 * @return int
	 */
	public function getWpId()
	{
		return intval($this->wpId);
	}

	/**
	 * @return mixed
	 */
	public function getDisplayPositions()
	{
		return esc_attr($this->displayPosition);
	}

	/**
	 * @return string - containing html code.
	 */
	public function getHeaderCode()
	{
		return $this->headerCode;
	}

	/**
	 * @return string
	 */
	public function getWordpressBlogId()
	{
		return esc_attr($this->wordpressBlogId);
	}


}