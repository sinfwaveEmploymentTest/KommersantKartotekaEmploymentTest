<?php

namespace Kommersant;

use PDO;
use PDOException;

/**
 * Takes MySQL data from test database via PHP PDO and converts to string
 * 
 * @method public __construct() creates connection to database via PDO and stores PDO instance to private property
 * @method public usersOfficesToString() creates formatted string from database query of users and offices
 * @method public officesWithMoreUsersToString() creates formatted string from database query of offices with more then 1 users
 */
class sqlUsersOfficesData
{
	/**
	 * PDO instance
	 */
	protected object $pdo;
	
	/**
	 * Creates PDO instance with class initialization
	 */
	public function __construct()
	{
		// can takes connect parameters from config if you wish
		$host = '127.0.0.1';
		$db   = 'test';
		$user = 'root';
		$pass = '32143214wp';
		$charset = 'utf8';
		
		// create dsn string and add useful options
		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
		$opt = [
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES		=> false,
		];
		
		// try to setup connections
		try
		{
			$this->pdo = new PDO($dsn, $user, $pass, $opt);
		}
		catch (PDOException $err)
		{
			die("Подключение к базе данных не удалось: {$err->getMessage()}");
		}
	}
	
	/**
	 * Makes select query to MySQL via class PDO instance and creates string with info of users and offices
	 *
	 * @return $usersOfficeDisplayString formated string, use to display data via echo output
	 */
	public function usersOfficesToString()
	{
		$usersOfficeArray = $this->pdo->query(
			"SELECT
			users.name AS 'UserName',
			offices.name AS 'OfficeName'
			FROM
				users
			JOIN offices ON users.office_id = offices.id
			ORDER BY users.name ASC"
		)->fetchAll();
		
		// string with header to concat
		$usersOfficeDisplayString = 'Имена пользователей и названия офисов, в которых они сидят:<br />';
		
		foreach( $usersOfficeArray as $key => $value )
		{
			$usersOfficeDisplayString .= "Имя пользователя: {$value['UserName']}, Название офиса: {$value['OfficeName']}<br />";
		}
		
		return $usersOfficeDisplayString;
	}
	
	/**
	 * Makes select query to MySQL via class PDO instance and creates string with info of offices with more then 1 users
	 *
	 * @return $officesDisplayString formated string, use to display data via echo output
	 */
	public function officesWithMoreUsersToString()
	{
		$officesArray = $this->pdo->query(
			"SELECT
				offices.name AS 'OfficeName'
			FROM
				users
			JOIN offices ON users.office_id = offices.id
			GROUP BY offices.name
			HAVING COUNT(offices.name) >= 2
			ORDER BY offices.name ASC;"
		)->fetchAll();
		
		// string with header to concat
		$officesDisplayString = 'Офисы с больше чем одним пользователем:<br />';
		
		foreach( $officesArray as $key => $value )
		{
			$officesDisplayString .= "Название офиса: {$value['OfficeName']}<br />";
		}
		
		return $officesDisplayString;
	}
}

// example use
$sqlUsersOfficesData = new sqlUsersOfficesData;

echo( $sqlUsersOfficesData->usersOfficesToString() );
echo( '<br />' );
echo( $sqlUsersOfficesData->officesWithMoreUsersToString() );