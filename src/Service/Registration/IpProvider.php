<?php


namespace Context360\Service\Registration;


class IpProvider
{
	public function getServerIp()
	{
		if ( isset( $_SERVER['SERVER_ADDR'] ))
		{
			return $_SERVER['SERVER_ADDR'];
		}
		return gethostbyname( $_SERVER['SERVER_NAME'] );
	}

}