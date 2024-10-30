<?php


namespace Context360\Model\Registration\API\OAuth2;


use Context360\Model\Registration\API\FailureApiResponse;
use Context360\Model\RestResponseInterface;

class OAuth2FailureResponse
	extends FailureApiResponse
	implements RestResponseInterface
{
	public function wasSuccessful()
	{
		return false;
	}
}