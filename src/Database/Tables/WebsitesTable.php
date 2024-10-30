<?php

namespace Context360\Database\Tables;

/**
 * Model representing websites table.
 */
class WebsitesTable extends AbstractTable
{
	protected $credentialsTable;

	const COL_ID               = 'application_id';
	const COL_HEADER_CODE      = 'header_code';
	const COL_DISPLAY_POSITION = 'display_position';
	const COL_WP_ID            = 'wp_id';
	const COL_ACCOUNTS_ID      = 'accounts_id';
	const COL_BLOG_ID          = 'blog_id';

	public function __construct()
	{
		$this->entity           = 'websites';
		$this->credentialsTable = new CredentialsTable();
	}

	public function createTableQuery()
	{
		return /** @lang MySQL */ "CREATE TABLE IF NOT EXISTS " . $this->getFullTableName() . " (
    		  " . self::COL_ID . "               VARCHAR(36) NOT NULL PRIMARY KEY
	        , " . self::COL_HEADER_CODE . "      TEXT NULL
	        , " . self::COL_DISPLAY_POSITION . " TEXT NULL
	        , " . self::COL_WP_ID . "            INT NULL
	        , " . self::COL_BLOG_ID . "     	 INT NULL 
	        , " . self::COL_ACCOUNTS_ID . "      INT NULL 
	            					" . $this->credentialsTable->getFkReference(CredentialsTable::COL_ID) . "
		);";
	}
}