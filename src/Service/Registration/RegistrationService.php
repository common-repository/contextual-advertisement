<?php


namespace Context360\Service\Registration;


use Context360\Database\CredentialsRepository;
use Context360\Database\WebsiteRepository;
use Context360\Model\Placements\Website;
use Context360\Model\Registration\API\Registration\RegistrationApiRequest;
use Context360\Model\Registration\API\Registration\RegistrationFailureResponse;
use Context360\Model\Registration\API\Registration\SuccessfulApiRegistration;
use Context360\Model\Registration\DB\RegistrationDatabaseFailure;
use Context360\Model\Registration\RegistrationRequest;
use Context360\Model\Registration\RegistrationSuccessfulResponse;
use Context360\Model\RestResponseInterface;

class RegistrationService
{
	protected $credentialsRepository;
	protected $websiteRepository;
	protected $credentialsApiClient;
	protected $ipService;

	/**
	 * RegistrationService constructor.
	 *
	 * @param CredentialsRepository $credentialsRepository
	 * @param WebsiteRepository $websiteRepository
	 * @param CredentialsApiClient $credentialsApiClient
	 * @param IpProvider $ipService
	 */
	public function __construct(
		CredentialsRepository $credentialsRepository,
		WebsiteRepository $websiteRepository,
		CredentialsApiClient $credentialsApiClient,
		IpProvider $ipService
	)
	{
		$this->credentialsRepository = $credentialsRepository;
		$this->credentialsApiClient  = $credentialsApiClient;
		$this->ipService             = $ipService;
		$this->websiteRepository     = $websiteRepository;
	}

	/**
	 * Register plugin.
	 *
	 * @param RegistrationRequest $registrationRequest
	 *
	 * @return RegistrationFailureResponse|RegistrationDatabaseFailure|RegistrationSuccessfulResponse
	 */
	public function registerWordpressApp(RegistrationRequest $registrationRequest)
	{
		$serverIp                = $this->ipService->getServerIp();
		$apiModel                = new RegistrationApiRequest($registrationRequest, $serverIp);
		$registrationApiResponse = $this->credentialsApiClient->registerApp($apiModel);


		if ($registrationApiResponse instanceof RegistrationFailureResponse)
		{
			return $registrationApiResponse;
		}
		$dbCredentialsResult = $this->credentialsRepository->addCredentials($registrationApiResponse);

		$clientCredentials = $this->credentialsRepository->getClientCredentials();
		if ( !$clientCredentials)
		{
			return new RegistrationDatabaseFailure();
		}
		$website = new Website($registrationApiResponse->getApplicationId(), get_current_user_id(), get_current_blog_id(), null);

		$dbWebsiteResult = $this->websiteRepository->addWebsite($website, $clientCredentials);
		if ( !$dbWebsiteResult || !$dbCredentialsResult)
		{
			return new RegistrationDatabaseFailure();
		}

		return new RegistrationSuccessfulResponse();
	}

}
