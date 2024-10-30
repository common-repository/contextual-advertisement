<?php


namespace Context360\Service\Placements;

use Context360\Model\Credentials\Credentials;
use Context360\Model\Placements\PlacementsFailureResponse;
use Context360\Model\Placements\Publisher;
use Context360\Service\AuthorizedApiClient;
use Context360\Service\OAuth2\OAuth2Service;

class PlacementsApiClient extends AuthorizedApiClient
{
	const APP_REGISTERED = 200;

	/***
	 * @param Credentials $credentials
	 * @param OAuth2Service $OAuth2TokenService
	 *
	 * @return PlacementsFailureResponse|Publisher
	 */
	public function getPlacements( Credentials $credentials, OAuth2Service $OAuth2TokenService )
	{
		$token              = $credentials->getToken();
		$path               = '/api/v1/publishers/placements';
		$url                = $this->apiHost . $path;
		$authorizedResponse = $this->get( $url, $token );

		if ( ! $authorizedResponse->isAuthorized() ) {
			$OAuth2TokenService->getCredentials();
			$authorizedResponse = $this->get( $url, $token );
		}
		$response = $authorizedResponse->getResponse();

		if ( $response instanceof \WP_Error ) {
			return new PlacementsFailureResponse( $response->errors['http_request_failed'][0], 500 );
		}
		if ( ! is_array( $response ) || $response['response']['code'] !== self::APP_REGISTERED ) {
			return new PlacementsFailureResponse( $response['response']['code'], $response['response']['message'] );
		}

		$publisherArray = json_decode( $response['body'], true );

		return new Publisher( $publisherArray );
	}


}