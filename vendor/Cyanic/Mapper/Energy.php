<?php

namespace Cyanic\Mapper;

use Cyanic\DbAdapter;

use DateTime;
use PDO;

/**
 *
 * Energy Mapper class
 *
 * @author Cyanic Webdesign
 * @copyright 2014
 *
 */
class Energy extends AbstractMapper
{
	protected $adapter;
	protected $classname = 'Cyanic\Model\Energy';
	protected $tablename = 'p1_data';
	protected $primaryKey = 'id';


	/**
	 *
	 * Constructor with factory patter for the database adapter
	 *
	 * @param $adapter
	 *
	 */
	public function __construct(DbAdapter $adapter)
	{
		$this->adapter = $adapter;
	}


	/**
	 *
	 *	Get the raw log
	 *
	 *	@param int $limit
	 *	@return array
	 *
	 */
	public function getLogs($limit = 100)
	{
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare(
			"SELECT * FROM p1_data WHERE 1 ORDER BY date_created DESC LIMIT " . $limit
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Get the current stats
	 *
	 */
	public function getCurrentStats()
	{
		//	initiate current date
		$date = new DateTime('now');

		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare(
			"SELECT date_created, ((MAX( t1_usage ) - MIN( t1_usage )) + (MAX( t2_usage ) - MIN( t2_usage ))) AS current_usage,
				((MAX( t1_restitution ) - MIN( t1_restitution )) + (MAX( t2_restitution ) - MIN( t2_restitution ))) AS current_restitution,
				(MAX( gas_usage ) - MIN( gas_usage )) AS gas_usage
				FROM `p1_data`
				WHERE date_created LIKE '" . $date->format('Y-m-d') . "%'"
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Get the day record
	 *
	 * @param string $column
	 * @return array
	 *
	 */
	public function getRecord($column = 't1_usage')
	{
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare(
			"SELECT date_created, gas_usage, (t1_usage + t2_usage) as current_usage, (t1_restitution + t2_restitution) as current_restitution
			FROM `p1_data_daily`
			ORDER BY $column DESC LIMIT 1
			"
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Get the daily logs between two dates
	 *
	 * @param datetime $dateStart
	 * @param datetime $dateEnd
	 * @return array $results
	 *
	 */
	public function getDailyLogs(DateTime $dateStart, DateTime $dateEnd)
	{
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare(
			"SELECT date_created, gas_usage, (t1_usage + t2_usage) as current_usage, (t1_restitution + t2_restitution) as current_restitution
			FROM `p1_data_daily`
			WHERE date_created >= '" . $dateStart->format('Y-m-d') . "'
			AND date_created <= '" . $dateEnd->format('Y-m-d') . "'
			ORDER BY date_created ASC
			"
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Get the monthly logs between two dates
	 *
	 * @param datetime $dateStart
	 * @param datetime $dateEnd
	 * @return array $results
	 *
	 */
	public function getMonthlyLogs(DateTime $dateStart, DateTime $dateEnd)
	{
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare(
			"SELECT date_format(date_created, '%Y-%m') as date_created, SUM(t1_usage + t2_usage) as current_usage,
				SUM(t1_restitution + t2_restitution) as current_restitution,
				SUM(gas_usage) as gas_usage
			FROM `p1_data_daily`
			WHERE date_created >= '" . $dateStart->format('Y-m') . "%'
			AND date_created <= '" . $dateEnd->format('Y-m') . "%'
			GROUP BY date_format(date_created, '%Y-%m')
			ORDER BY date_created ASC
			"
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Get the yearly logs
	 *
	 * @return array $results
	 *
	 */
	public function getYearlyLogs()
	{
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare(
			"SELECT date_format(date_created, '%Y') as date_created, SUM(t1_usage + t2_usage) as current_usage,
				SUM(t1_restitution + t2_restitution) as current_restitution,
				SUM(gas_usage) as gas_usage
			FROM `p1_data_daily`
			GROUP BY date_format(date_created, '%Y')
			ORDER BY date_created ASC
			"
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	public function getContractLogs(DateTime $dateStart)
	{
		$results = array();
		$years = date('Y') - $dateStart->format('Y');
		for($t=0; $t <= $years; $t++) {

			$start = date('Y-m-d', strtotime($dateStart->format('Y-m-d') . " + $t year"));
			$end = date('Y-m-d', strtotime($start . " + 364 days"));

			//	prepare the select statement
			$query = $this->adapter->getConnection()->prepare(
				"SELECT SUM(t1_usage + t2_usage) as current_usage, SUM(t1_restitution + t2_restitution) as current_restitution, SUM(gas_usage) as gas_usage
				 FROM `p1_data_daily`
				 WHERE date_created BETWEEN '" . $start . "' AND '" . $end . "'
				 ORDER BY date_created ASC
				"
			);
			//	bind the params
			$query->execute();

			//	fetch all the rows and return an array
			$rows = $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
			$rows[0]->setdateCreated($start);
			$results[] = $rows[0];
		}

		return $results;
	}



	/**
	 *
	 * Get the raw p1 logs grouped by day
	 *
	 * @param datetime $date
	 * @return array $results
	 *
	 */
	public function getDailyRawLogs(DateTime $date)
	{
		$query = $this->adapter->getConnection()->prepare(
			"SELECT id, date_format(date_created, '%Y-%m-%d') as date_created, t1_usage, t2_usage, t1_restitution, t2_restitution, gas_usage
			FROM `p1_data` d
			INNER JOIN (SELECT MIN(date_created) as min FROM p1_data WHERE date_created > '" . $date->format('Y-m-d H:i:s') . "' GROUP BY date_format(date_created, '%Y-%m-%d')) pd
			ON d.date_created = pd.min
			ORDER BY `d`.`date_created`  ASC"
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Find a daily log
	 *
	 * @return int count
	 *
	 */
	public function findDailyLog($dateCreated)
	{
		$query = $this->adapter->getConnection()->prepare(
			"SELECT id
			FROM `p1_data_daily`
			WHERE date_created = '" . $dateCreated->format('Y-m-d') . "'"
		);
		//	bind the params
		$query->execute();

		return count($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Save a daily log when not already exists
	 *
	 * @param Enery $log
	 *
	 */
	public function saveDailyLog($log)
	{
		//	only new created logs
		if($this->findDailyLog($log->getDateCreated()) == 0) {
			$query = $this->adapter->getConnection()->prepare(
				"INSERT INTO `p1_data_daily` (date_created, t1_usage, t2_usage, t1_restitution, t2_restitution, gas_usage)
				 VALUES ('" . $log->getDateCreated()->format('Y-m-d') . "', '" . $log->getT1Usage() . "', '" . $log->getT2Usage() . "',
				'" . $log->getT1Restitution() . "', '" . $log->getT2Restitution() . "', '" . $log->getGasUsage() . "')"
			);
			//	bind the params
			$query->execute();
		}
	}


	/**
	 *
	 * Get the hourly gas usage logs
	 *
	 * @param int $limit
	 * @return array $logs
	 *
	 */
	public function getGasUsageLogs($limit)
	{
		//	prepare the select statement
		$query = $this->adapter->getConnection()->prepare(
			"SELECT `id`, `date_created`, `t1_usage`, `t2_usage`, `t1_restitution`, `t2_restitution`, `current_usage`, `current_restitution`, `gas_usage`, `rate`
			FROM p1_data
			WHERE DATE_FORMAT(date_created, '%i') = '00'
			ORDER BY date_created DESC
			LIMIT " . $limit
		);
		//	bind the params
		$query->execute();

		//	fetch all the rows and return an array
		return $this->toObjectArray($query->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 *
	 * Get the differences between to log rows
	 *
	 * @param array $logs
	 * @return array $differences
	 *
	 */
	public function getDifferences(array $logs)
	{
		$differences = array();
		for($i=0; $i<count($logs); $i++) {
			if(isset($logs[$i+1])) {
				$differences[$i] = $this->toObject(array(
					'dateCreated' => $logs[$i]->getDateCreated(),
					't1Usage' => number_format($logs[$i+1]->getT1Usage() - $logs[$i]->getT1Usage(), 2),
					't2Usage' => number_format($logs[$i+1]->getT2Usage() - $logs[$i]->getT2Usage(), 2),
					't1Restitution' => number_format($logs[$i+1]->getT1Restitution() - $logs[$i]->getT1Restitution(), 2),
					't2Restitution' => number_format($logs[$i+1]->getT2Restitution() - $logs[$i]->getT2Restitution(), 2),
					'gasUsage' => number_format($logs[$i+1]->getGasUsage() - $logs[$i]->getGasUsage(), 2),
				));
			}
		}
		return $differences;
	}


	/**
	 *
	 * Create a json string
	 *
	 * @param array $logs
	 * @param string $column	 *
	 * @return string json
	 *
	 */
	public function toJson(array $logs, $column = 't1Usage', $negative  = false)
	{
		//	walkthrough logs and fill the result array
		$result = array();
		foreach($logs as $key => $log) {
			if($negative === true) {
				$result[] = '[' . $log->getDateCreated()->format('U') . '000, -'. number_format($log->$column, 2, '.', '') . ']';
			} else {
				$result[] = '[' . $log->getDateCreated()->format('U') . '000, '. number_format($log->$column, 2, '.', '') . ']';
			}
		}

		//	remove the double quotes
		return str_replace('"', '', json_encode($result));
	}



	/**
	 *
	 * Implement abstract functions
	 *
	 */
	public function getClassname()
	{
		return $this->classname;
	}
	public function getTablename()
	{
		return $this->tablename;
	}
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}
}
