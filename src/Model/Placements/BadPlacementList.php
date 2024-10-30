<?php


namespace Context360\Model\Placements;


class BadPlacementList extends PlacementList
{
	public function wasSuccessful()
	{
		return false;
	}
}