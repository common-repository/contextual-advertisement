<?php


namespace Context360\Service\Registration;


use Context360\Model\Registration\API\Registration\RegistrationFailureResponse;
use Context360\Model\Registration\API\Registration\CategoriesCollection;
use WP_Error;

class CategoriesApiClient
{
	const APP_REGISTERED = 200;

	protected $apiHost;

	/**
	 * OAuth2ApiClient constructor.
	 *
	 * @param $apiHost
	 */
	public function __construct( $apiHost )
	{
		$this->apiHost = $apiHost;
	}

	/**
	 * @return RegistrationFailureResponse|CategoriesCollection
	 */
	public function categoryApp()
	{
		$path     = '/api/v1/categories';
		$url      = $this->apiHost . $path;
		$response = wp_remote_request( $url, [
			'method'  => 'GET',
			'headers' => [
				'Content-Type' => 'application/json'
			],
		] );
		if ( $response instanceof WP_Error ) {
			return new RegistrationFailureResponse( $response->errors['http_request_failed'][0], 500 );
		}
		if ( ! is_array( $response ) || $response['response']['code'] !== self::APP_REGISTERED ) {
			return new RegistrationFailureResponse( $response['response']['message'], $response['response']['code'] );
		}

		$result = json_decode( $response['body'], true );

		return new CategoriesCollection( $result );
	}


}