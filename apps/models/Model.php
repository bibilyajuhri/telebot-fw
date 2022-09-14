<?php 

namespace apps\models;

use apps\db\DbConnection;
use apps\db\QueryBuilder;

class Model{
	private $db;

	function __construct($called_class = null){
		if($called_class){
			if(method_exists($called_class, 'getDb')){
				$getDb = $called_class::getDb();
				$this->db = DbConnection::getInstance($getDb);
			}
		}else{
			$getDb = get_called_class()::getDb();
			$this->db = DbConnection::getInstance($getDb);
		}
	}

	public function runBuilderQuery($query, $called_class, $one = false, $selectCount = false){
		$sql = $this->db->query($query);
		if(!$sql){
			throw new \Exception("Error Sql\n".$this->db->error, 1);
			return false;
		}
		$fetch = $this->db->fetch($sql);
		if($selectCount){
			return (int)$fetch[0]['count'];
		}
		$return = null;
		if(is_array($fetch)){
			foreach ($fetch as $key1 => $value1) {
				$instance = new $called_class;
				foreach ($value1 as $key2 => $value2) {
					if($value2 == 't' || $value2 == 'f'){
						$instance->$key2 = $value2 == 't' ? true : false;
					}else{
						$instance->$key2 = $value2;
					}
				}
				$return[$key1] = $instance;
			}
		}else{
			$instance = new $called_class;
			foreach ($fetch as $key => $value) {
				if($value == 't' || $value == 'f'){
					$instance->$key = $value == 't' ? true : false;
				}else{
					$instance->$key = $value;
				}
			}
			$return = $instance;
		}

		if($one && !is_null($return) && count($return) == 1){
			return $return[0];
		}
		return $return;
	}

	public function save(){
		$insertColumn = [];
		$insertValue = [];
		foreach ($this as $key => $value) {
			if(is_string($value) || is_int($value)){
				$insertColumn[] = $key;
				$insertValue[] = is_string($value) ? "'$value'" : "$value";
			}else
			if(is_bool($value)){
				$insertColumn[] = $key;
				$insertValue[] = $value ? 'true' : 'false';
			}
		}
		$query = "INSERT INTO ".static::tableName()." (".implode(", ", $insertColumn).") VALUES (".implode(", ", $insertValue).")";
		$insert = $this->db->insert($query);
		return $insert;
	}

	public function saveBulk(Array $column, Array $values){
		$insertColumn = [];
		$insertValue = [];
		$valArr = [];
		foreach ($values as $key => $value) {
			$val = "(";
			foreach ($value as $key2 => $value2) {
				$separator = $key2 ? ", " : "";
				$val .= $separator;
				if(is_string($value2) || is_int($value2)){
					$val .= is_string($value2) ? "'$value2'" : "$value2";
				}else
				if(is_bool($value2)){
					$val .= $value2 ? 'true' : 'false';
				}
			}
			$val .= ")";
			$valArr[] = $val;
		}
		$query = "INSERT INTO ".static::tableName()." (".implode(", ", $column).") VALUES ".implode(",", $valArr);
		$insert = $this->db->insert($query);
		return $insert;
	}

	public function update(){ // single record
		$query = "UPDATE ".static::tableName()." SET";
		$first = true;
		$where = 'where';
		foreach ($this as $key => $value) {
			if($key == 'id'){
				$where .= " $key = $value";
				continue;
			}
			$separator = $first ? " " : ", ";
			if(is_string($value) || is_int($value)){
				$query .= is_int($value) ? "$separator$key = $value" : "$separator$key = '$value'";
				$first = false;
			}else
			if(is_bool($value)){
				$query .= $value ? "$separator$key = true" : "$separator$key = false";
				$first = false;
			}
		}
		$query = $query.' '.$where;
		$update = $this->db->update($query);
		return $update;
	}

	public function updateBulk($set, $condition){
		$query = "UPDATE ".static::tableName()." SET";
		$first = true;
		foreach ($set as $key => $value) {
			$separator = $first ? " " : ", ";
			if(is_string($value) || is_int($value)){
				$query .= is_int($value) ? "$separator$key = $value" : "$separator$key = '$value'";
				$first = false;
			}else
			if(is_bool($value)){
				$query .= $value ? "$separator$key = true" : "$separator$key = false";
				$first = false;
			}
		}
		$query .= " where ".$condition;
		$update = $this->db->update($query);
		return $update;
	}

	public function delete(){
		$query = "DELETE from ".static::tableName()." where id = ".$this->id;
		$delete = $this->db->delete($query);
		return $delete;
	}

	public function deleteBulk($condition){
		$query = "DELETE from ".static::tableName()." where ".$condition;
		$delete = $this->db->delete($query);
		return $delete;
	}

	public static function find(){
		return new QueryBuilder(static::tableName(), get_called_class());
	}

	public static function findOne($whereClause){
		return (new QueryBuilder(static::tableName(), get_called_class(), $whereClause))->one();
	}

	public static function findAll($whereClause){
		return (new QueryBuilder(static::tableName(), get_called_class(), $whereClause))->all();
	}
}