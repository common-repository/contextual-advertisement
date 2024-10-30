<?php


namespace Context360\Model\Registration\API\OAuth2;

use Context360\Model\Registration\OAuth2Request;

class OAuth2ApiRequest
{
	protected $oAuth2Request;

	/**
	 * OAuth2Invoice constructor.
	 *
	 * @param $oAuth2Request
	 */
	public function __construct( OAuth2Request $oAuth2Request )
	{
		$this->oAuth2Request = $oAuth2Request;
	}

	public function toUrlEncoded()
	{
		$a = [
			'grant_type' => $this->oAuth2Request->getGrantType(),
			'scope'      => $this->oAuth2Request->getScope()
		];
		$b = [];
		foreach ( $a as $key => $value ) {
			$b[] = $key . '=' . $value;
		}

		return implode( '&', $b );
	}

}