<?php


namespace Context360\Database;

use Context360\Database\Tables\PlacementsTable as PTable;
use Context360\Model\Placements\Placement;
use Context360\Model\Placements\PlacementList;
use Context360\Model\Placements\Website;

/**
 * Manages placements list.
 */
class PlacementsRepository extends AbstractDatabaseRepository
{
	protected $placementsTable;

	/**
	 * {@inheritDoc}
	 */
	public function __construct($wpdb)
	{
		parent::__construct($wpdb);
		$this->placementsTable = new PTable();
	}

	public function createTable()
	{
		$query = $this->placementsTable->createTableQuery();
		$this->executeRawQuery($query);
	}

	/**
	 * @param Placement $placement
	 *
	 * @param Website $website
	 *
	 * @return int|false - The number of rows inserted, or false on error.
	 */
	public function addPlacement(Placement $placement, Website $website)
	{
		$insert = [
			PTable::COL_ID             => $placement->getPlacementId(),
			PTable::COL_HTML_CODE      => $placement->getHtmlCode(),
			PTable::COL_WEBSITE_ID     => $website->getApplicationId(),
			PTable::COL_IS_DEFAULT     => $placement->getIsDefault(),
			PTable::COL_PLACEMENT_NAME => $placement->getPlacementName(),
		];

		return $this->insert($this->placementsTable->getFullTableName(), $insert);
	}

	public function setAllPlacementDefault()
	{
		$update = [
			PTable::COL_IS_DEFAULT => 0
		];
		$where  = [
			PTable::COL_IS_DEFAULT => 1
		];

		return $this->update($this->placementsTable->getFullTableName(), $update, $where);
	}

	public function removeAllPlacements()
	{
		$query = "TRUNCATE TABLE " . $this->placementsTable->getFullTableName();

		return $this->query($query);
	}

	public function updatePlacement(Placement $placement, Website $website)
	{
		$update = [
			PTable::COL_ID             => $placement->getPlacementId(),
			PTable::COL_PLACEMENT_NAME => $placement->getPlacementName(),
			PTable::COL_HTML_CODE      => $placement->getHtmlCode(),
			PTable::COL_WEBSITE_ID     => $website->getApplicationId(),
		];
		$where  = [];

		return $this->update($this->placementsTable->getFullTableName(), $update, $where);
	}

	/**
	 * @return PlacementList
	 */
	public function getPlacements()
	{
		$query   = "SELECT 
       		" . PTable::COL_ID . " , 
       		" . PTable::COL_PLACEMENT_NAME . " , 
       		" . PTable::COL_HTML_CODE . " ,
       		" . PTable::COL_IS_DEFAULT . " 
		FROM  " . $this->placementsTable->getFullTableName();
		$results = $this->query($query);

		if (count($results) === 0)
		{
			return new PlacementList();
		}

		$placementList = new PlacementList();
		foreach ($results as $result)
		{
			$placementList->addPlacement(new Placement(
				$result->placement_id, $result->placement_name, $result->html_code, $result->is_default
			));
		}

		return $placementList;
	}

	public function dropTable()
	{
		$query = 'DROP TABLE ' . $this->placementsTable->getFullTableName();
		$this->executeRawQuery($query);
	}
}