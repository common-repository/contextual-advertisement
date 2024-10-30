<?php
namespace Context360\Service\Registration;

use Context360\Model\Registration\API\Registration\RegistrationFailureResponse;
use Context360\Model\Registration\API\Registration\CategoriesCollection;


class CategoriesService
{
	protected $categoriesApiClient;

	/**
	 * CategoriesService constructor.
	 *
	 * @param $categoriesApiClient
	 */
	public function __construct( CategoriesApiClient $categoriesApiClient )
	{
		$this->categoriesApiClient = $categoriesApiClient;
	}

	/**
	 *
	 * @return CategoriesCollection
	 */
	public function getCategories( )
	{
		$response = $this->categoriesApiClient->categoryApp();

		if ( $response instanceof RegistrationFailureResponse ) {
			return new CategoriesCollection([]);
		}

		return $response;
	}
}