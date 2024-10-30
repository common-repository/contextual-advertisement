<?php


namespace Context360\Model\Placements;


use Context360\Model\RestResponseInterface;

class PlacementLocationUpdatedResponse implements RestResponseInterface
{
	protected $code = 200;
	protected $message = 'Placements successfully updated';

	/**
	 * PlacementLocationUpdatedResponse constructor.
	 *
	 * @param int $code
	 * @param string $message
	 */
	public function __construct($code = 200, $message = '')
	{
		$this->code    = $code;
		$this->message = empty($message) ? $this->message : $message;
	}


	public function getCode()
	{
		return $this->code;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function wasSuccessful()
	{
		return true;
	}
}