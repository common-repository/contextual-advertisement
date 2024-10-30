<?php


namespace Context360\Controller\Rest;


use Context360\Controller\AbstractRestController;
use Context360\Model\Placements\PlacementListRequest;
use Context360\Service\Placements\ListPlacementService;
use Context360\Service\Registration\RegistrationService;

/**
 * Handles placements themselves.
 */
class PlacementsRestController extends AbstractRestController
{
	protected $listPlacementService;
	protected $registrationService;

	/**
	 * PlacementsListController constructor.
	 *
	 * @param ListPlacementService $listPlacementService
	 * @param RegistrationService $registrationService
	 */
	public function __construct(ListPlacementService $listPlacementService, RegistrationService $registrationService)
	{
		$this->listPlacementService = $listPlacementService;
		$this->registrationService  = $registrationService;
	}

	/**
	 * @param $request
	 *
	 * @return array
	 */
	protected function handle($request)
	{
		$model = new PlacementListRequest(
			sanitize_text_field($this->getRequestField($request, 'placementId'))
		);

		$validationResult = $model->validate();
		if ( !$validationResult->isValid())
		{
			return $validationResult->getErrorMessages();
		}

		$restResponse = $this->listPlacementService->checkedPlacementPosition($model);

		if ( !$restResponse->wasSuccessful())
		{
			return [
				'message' => 'Unknown error',
				'status'  => 500,
				'success' => $restResponse->wasSuccessful()
			];
		}

		return [
			'message' => 'Placement status changed',
			'status'  => 200,
			'success' => $restResponse->wasSuccessful()
		];
	}
}