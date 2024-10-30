<?php

namespace Context360\Model\Registration;

use Context360\Model\RestResponseInterface;

/**
 * Response, that occurs, when registration have been registered successfully.
 */
class RegistrationSuccessfulResponse implements RestResponseInterface
{
	protected $message = 'Registration request sent';
	protected $code    = 200;

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return bool
	 */
	public function wasSuccessful()
	{
		return true;
	}
}