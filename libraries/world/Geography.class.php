<?php

//$connMainAdodb->debug = true;
class world_Geography
{
	public function countryList()
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select * from geo_countries order by name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_YEAR, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['con_id'], 'country' => $recordSet->fields['name']);
			$recordSet->MoveNext();
		}
		return $return;
	}

	public function stateList($country_id)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select * from geo_states WHERE con_id = ".$connMainAdodb->qstr($country_id)." order by name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_YEAR, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'state' => $recordSet->fields['name']);
			$recordSet->MoveNext();
		}
		return $return;
	}

	public function cityList($state_id)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select * from geo_cities WHERE sta_id = ".$connMainAdodb->qstr($state_id)." order by name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_WEEK, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['cty_id'], 'sta_id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'city' => $recordSet->fields['name'], 'latitude' => $recordSet->fields['latitude'], 'longitude' => $recordSet->fields['longitude']);
			$recordSet->MoveNext();
		}
		return $return;
	}

	public function cityDetail($city_id)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select geo_cities.*, geo_states.name as statename, geo_countries.name as countryname from geo_cities left join geo_states on geo_cities.sta_id = geo_states.sta_id  left join geo_countries on geo_cities.con_id = geo_countries.con_id WHERE geo_cities.cty_id = ".$connMainAdodb->qstr($city_id)." order by geo_cities.name, geo_states.name, geo_countries.name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_WEEK, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['cty_id'], 'sta_id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'city' => $recordSet->fields['name'], 'latitude' => $recordSet->fields['latitude'], 'longitude' => $recordSet->fields['longitude'], 'statename' => $recordSet->fields['statename'], 'countryname' => $recordSet->fields['countryname']);
			$recordSet->MoveNext();
		}
		return $return;
	}
	

	public function findcity($keyword='')
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
    $city = '%'.$keyword.'%';
		$sql = "select geo_cities.*, geo_states.name as statename, geo_countries.name as countryname from geo_cities left join geo_states on geo_cities.sta_id = geo_states.sta_id  left join geo_countries on geo_cities.con_id = geo_countries.con_id WHERE geo_cities.name like ".$connMainAdodb->qstr($city)." order by geo_cities.name, geo_states.name, geo_countries.name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_WEEK, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['cty_id'], 'sta_id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'city' => $recordSet->fields['name'], 'latitude' => $recordSet->fields['latitude'], 'longitude' => $recordSet->fields['longitude'], 'statename' => $recordSet->fields['statename'], 'countryname' => $recordSet->fields['countryname']);
			$recordSet->MoveNext();
		}
		return $return;
	}
	
	public function get_nearby_cities($lat, $lon, $radius=30, $order='distance', $limit=30)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = sprintf("select *, (ROUND(
	DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($lat, 'double').")) * SIN(RADIANS(c.latitude)) + COS(RADIANS(".GetSQLValueString($lat, 'double').")) * COS(RADIANS(c.latitude)) * COS(RADIANS(".GetSQLValueString($lon, 'double')." -(c.longitude)))))*60*1.1515,2)) as distance from geo_cities as c WHERE (ROUND(
	DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($lat, 'double').")) * SIN(RADIANS(c.latitude)) + COS(RADIANS(".GetSQLValueString($lat, 'double').")) * COS(RADIANS(c.latitude)) * COS(RADIANS(".GetSQLValueString($lon, 'double')." -(c.longitude)))))*60*1.1515,2)) <= ".GetSQLValueString($radius, 'int')." ORDER BY ".$order." LIMIT ".$limit);
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_DAY, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return['city_'.$recordSet->fields['cty_id']] = $recordSet->fields;
			$recordSet->MoveNext();
		}
		return $return;
	}
}

?>