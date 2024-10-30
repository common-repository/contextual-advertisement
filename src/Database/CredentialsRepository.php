<?php

namespace Context360\Database;

use Context360\Database\Tables\CredentialsTable as CTable;
use Context360\Model\Credentials\Credentials;
use Context360\Model\Registration\API\OAuth2\SuccessfulApiOAuth2;
use Context360\Model\Registration\API\Registration\SuccessfulApiRegistration;

/**
 * Manages plugin credentials.
 */
class CredentialsRepository extends AbstractDatabaseRepository
{
	protected $credentialsTable;

	public function __construct($wpdb)
	{
		parent::__construct($wpdb);
		$this->credentialsTable = new CTable();
	}

	public function createTable()
	{
		$query = $this->credentialsTable->createTableQuery();
		$this->executeRawQuery($query);
	}

	/**
	 * @param SuccessfulApiRegistration $successfulApiRegistration
	 *
	 * @return int|false - The number of rows inserted, or false on error.
	 */
	public function addCredentials(SuccessfulApiRegistration $successfulApiRegistration)
	{
		$insert = [
			CTable::COL_CLIENT_SECRET => $successfulApiRegistration->getClientSecret(),
			CTable::COL_CLIENT_ID     => $successfulApiRegistration->getClientId(),
		];

		return $this->insert($this->credentialsTable->getFullTableName(), $insert);
	}

	/**
	 * @param SuccessfulApiOAuth2 $successfulApiOAuth2
	 * @param $clientId
	 *
	 * @return int|false - The number of rows inserted, or false on error.
	 */
	public function addToken(SuccessfulApiOAuth2 $successfulApiOAuth2, $clientId)
	{
		$update = [
			CTable::COL_ACCESS_TOKEN => $successfulApiOAuth2->getToken(),
			CTable::COL_EXPIRE_IN    => date('Y-m-d-H:i:s', $successfulApiOAuth2->getExpireIn() + time())
		];
		$where  = [
			CTable::COL_CLIENT_ID => $clientId
		];

		return $this->update($this->getTableName(), $update, $where);
	}

	/**
	 * @return Credentials
	 */
	public function getClientCredentials()
	{
		$query = "SELECT 
       		" . CTable::COL_ID . " , 
       		" . CTable::COL_CLIENT_ID . " , 
       		" . CTable::COL_CLIENT_SECRET . " , 
       		" . CTable::COL_ACCESS_TOKEN . " , 
       		" . CTable::COL_EXPIRE_IN . " , 
       		" . CTable::COL_PUBLISHER_ID . "
		FROM  " . $this->getTableName() . " 
		LIMIT 1 ";

		$results = $this->query($query);
		if (count($results) === 0)
		{
			return new Credentials();
		}
		$row = $results[0];

		return new Credentials($row->id, $row->client_id, $row->client_secret, $row->access_token, $row->expire_in, $row->publisher_id);
	}


	public function dropTable()
	{
		$query = 'DROP TABLE ' . $this->getTableName();
		$this->executeRawQuery($query);
	}

	public function setPublisher($publisherId, Credentials $clientCredentials)
	{
		$update = [
			CTable::COL_PUBLISHER_ID => $publisherId
		];
		$where  = [
			CTable::COL_CLIENT_ID => $clientCredentials->getClientId()
		];

		return $this->update($this->getTableName(), $update, $where);

	}

	private function getTableName() {
		return $this->credentialsTable->getFullTableName();
	}
}