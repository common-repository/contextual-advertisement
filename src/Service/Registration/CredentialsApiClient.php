<?php

namespace Context360\Service\Registration;

use Context360\Model\Registration\API\Registration\RegistrationApiRequest;
use Context360\Model\Registration\API\Registration\RegistrationFailureResponse;
use Context360\Model\Registration\API\Registration\SuccessfulApiRegistration;
use Context360\Model\RestResponseInterface;

class CredentialsApiClient
{
	const APP_REGISTERED = 201;

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
	 * @param RegistrationApiRequest $registrationApiRequest
	 *
	 * @return RegistrationFailureResponse|SuccessfulApiRegistration|RestResponseInterface
	 */
	public function registerApp( RegistrationApiRequest $registrationApiRequest )
	{
		$path     = '/api/v1/registrations/wp-plugin';
		$url      = $this->apiHost . $path;
		$response = wp_remote_request( $url, [
			'method'  => 'POST',
			'headers' => [
				'Content-Type' => 'application/json'
			],
			'body'    => json_encode( $registrationApiRequest->toArray() )
		] );
		if ( $response instanceof \WP_Error ) {
			return new RegistrationFailureResponse( $response->errors['http_request_failed'][0], 500 );
		}
		if ( ! is_array( $response ) || $response['response']['code'] !== self::APP_REGISTERED ) {
			return new RegistrationFailureResponse( $response['response']['message'], $response['response']['code']);
		}

		$result = json_decode( $response['body'], true );
		return new SuccessfulApiRegistration( $result['clientId'], $result['clientSecret'], $result['applicationId'], '' );
	}
}