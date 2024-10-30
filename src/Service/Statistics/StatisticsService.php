<?php


namespace Context360\Service\Statistics;


use Context360\Database\CredentialsRepository;
use Context360\Model\Placements\PlacementsFailureResponse;
use Context360\Model\Statistics\StatisticsList;
use Context360\Service\OAuth2\OAuth2Service;

class StatisticsService
{
	protected $credentialsRepository;
	protected $statisticsApiClient;
	protected $oAuth2Service;

	/**
	 * PlacementsService constructor.
	 *
	 * @param CredentialsRepository $credentialsRepository
	 * @param OAuth2Service $oAuth2Service
	 * @param StatisticsApiClient $statisticsApiClient
	 */
	public function __construct(
		CredentialsRepository $credentialsRepository,
		OAuth2Service $oAuth2Service,
		StatisticsApiClient $statisticsApiClient
	)
	{
		$this->credentialsRepository = $credentialsRepository;
		$this->statisticsApiClient   = $statisticsApiClient;
		$this->oAuth2Service         = $oAuth2Service;
	}

	/**
	 * @return StatisticsList
	 */
	public function getStatistics()
	{
		$clientInformation   = $this->credentialsRepository->getClientCredentials();
		$statisticsApiResult = $this->statisticsApiClient->getStatistics($clientInformation->getToken(), $this->oAuth2Service);
		if ($statisticsApiResult instanceof PlacementsFailureResponse)
		{
			$list = new StatisticsList();
			$list->setWasSuccessful(false);

			return $list;
		}

		return $statisticsApiResult;

	}
}