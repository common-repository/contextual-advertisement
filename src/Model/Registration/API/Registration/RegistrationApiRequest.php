<?php
namespace Context360\Model\Registration\API\Registration;

use Context360\Model\Registration\RegistrationRequest;

/**
 * Registration request sent to API.
 */
class RegistrationApiRequest
{
	protected $registrationRequest;
	protected $serverIp;

	/**
	 * RegistrationInvoice constructor.
	 *
	 * @param $registrationRequest
	 * @param $serverIp
	 */
	public function __construct( RegistrationRequest $registrationRequest, $serverIp )
	{
		$this->registrationRequest = $registrationRequest;
		$this->serverIp            = $serverIp;
	}

	public function toArray()
	{
		return [
			'login'             => $this->registrationRequest->getEmail(),
			'websiteUrl'        => $this->registrationRequest->getHost(),
			'requestIp'         => $this->serverIp,
			'token'             => $this->registrationRequest->getToken(),
			'websiteCategories' => $this->registrationRequest->getCategories(),
		];
	}
}