<?php

namespace Context360\Container;

class DependencyNotFoundException extends \RuntimeException
{
	/**
	 * DependencyNotFoundException constructor.
	 *
	 * @param string $message
	 */
	public function __construct( $message )
	{
		parent::__construct( $message );
	}


}