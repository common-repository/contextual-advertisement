<?php
namespace Context360\Model\Placements;

use Context360\Model\Registration\API\FailureApiResponse;
use Context360\Model\RestResponseInterface;

class PlacementsFailureResponse
	extends FailureApiResponse
	implements RestResponseInterface
{
	public function wasSuccessful()
	{
		return false;
	}
}