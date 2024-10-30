<?php


namespace Context360\Controller\Rest;

use Context360\Controller\AbstractRestController;
use Context360\Model\Registration\RegistrationRequest;
use Context360\Service\Registration\RegistrationService;

/**
 * Handles registration part.
 */
class RegistrationRestController extends AbstractRestController
{
	protected $registrationService;

	public function __construct(RegistrationService $registrationService)
	{
		$this->registrationService = $registrationService;
	}

	protected function handle($request)
	{
		$model = new RegistrationRequest(
			sanitize_text_field($this->getRequestField($request, 'token')),
			sanitize_email($this->getRequestField($request, 'email')),
			esc_url_raw($this->getRequestField($request, 'host')),
			sanitize_text_field($this->getRequestField($request, 'categories'))
		);

		$validationResult = $model->validate();
		if ( !$validationResult->isValid())
		{
			return $validationResult->getErrorMessages();
		}

		$restResponse = $this->registrationService->registerWordpressApp($model);

		if ( !$restResponse->wasSuccessful())
		{
			return [
				'message' => 'Could not register application. ' . $restResponse->getMessage(),
				'status'  => 500,
				'success' => $restResponse->wasSuccessful()
			];
		}

		return [
			'message' => $restResponse->getMessage(),
			'status'  => $restResponse->getCode(),
			'success' => $restResponse->wasSuccessful()
		];
	}
}