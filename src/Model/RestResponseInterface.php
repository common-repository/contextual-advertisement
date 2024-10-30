<?php


namespace Context360\Model;


interface RestResponseInterface
{
	public function getCode();
	public function getMessage();
	public function wasSuccessful();
}