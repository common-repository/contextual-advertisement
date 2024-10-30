<?php


namespace Context360\Service;


use Context360\Model\AuthorizedResponse;

class AuthorizedApiClient
{
	protected $apiHost;

	/**
	 * AuthorizedApiClient constructor.
	 *
	 * @param $apiHost
	 */
	public function __construct( $apiHost )
	{
		$this->apiHost = $apiHost;
	}

	/**
	 * @param $url
	 * @param $token
	 * @param array $options
	 *
	 * @return AuthorizedResponse
	 */
	public function get( $url, $token, $options = [] )
	{
		$response = wp_remote_request( $url, [
			'method'  => 'GET',
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $token
			],
		]);
		$status = wp_remote_retrieve_response_code( $response );

		return new AuthorizedResponse($status, $response);
	}
}