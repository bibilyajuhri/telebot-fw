<?php

namespace apps\db;

use apps\models\Model;

class QueryBuilder{

	public $tableName;
	private $model;
	private $called_class;

	private $select;
	private $join;
	private $where;
	private $order;
	private $group;
	private $limit;
	private $offset;
	private $rawQuery;

	public function __construct($tableName, $called_class, $whereClause = null){
		$this->called_class = $called_class;
		$this->model = new Model($called_class);
		$this->tableName = $tableName;
		$this->select("$this->tableName.*");
		if($whereClause){
			if(is_array($whereClause)){
				$this->andWhere($whereClause);
			}else{
				$this->andWhere(['id'=> $whereClause]);
			}
		}
	}

	public function rawWhere($where){
		if(count($where) == 1){
			foreach ($where as $key => $value) {
				if(!is_string($value) && is_numeric($value)){
					return "($key = $value)";
				}else
				if(is_bool($value)){
					if($value){
						return "($key = true)";
					}
					return "($key = false)";
				}
				return "($key = '$value')";
			}
		}else{
			switch ($where[0]) {
				case 'between':
					if( (!is_string($where[2]) && is_numeric($where[2])) || (!is_string($where[3]) && is_numeric($where[3])) ){
						return "($where[1] $where[0] $where[2] and $where[3])";
					}
					return "($where[1] $where[0] '$where[2]' and '$where[3]')";
					break;
				case 'in':
					return "($where[1] $where[0] (".implode(',', $where[2])."))";
					break;
				
				default:
					if(!is_string($where[2]) && is_numeric($where[2])){
						return "($where[1] $where[0] $where[2])";
					}else
					if(is_bool($where[2])){
						if($where[2]){
							return "($where[1] $where[0] true)";
						}
						return "($where[1] $where[0] false)";
					}
					return "($where[1] $where[0] '$where[2]')";
					break;
				return null;
			}
		}
	}

	public function select($select){
		$this->select = "SELECT $select FROM $this->tableName";
		return $this;
	}

	public function where($where){
		$this->where = '';
		if(is_array($where)){
			$this->where = 'where '.$this->rawWhere($where);
		}else{
			$this->where = 'where ('.$where.')';
		}
		return $this;
	}

	public function andWhere($where){
		// $this->where = '';
		if(!$this->where){
			$separator = 'where';
		}else{
			$separator = ' and';
		}

		if(is_array($where)){
			$this->where .= "$separator ".$this->rawWhere($where);
		}else{
			$this->where .= "$separator (".$where.")";
		}
		return $this;
	}

	public function leftJoin(Array $join){
		return $this->join(['left', $join[0], $join[1]]);
	}

	public function rightJoin(Array $join){
		return $this->join(['right', $join[0], $join[1]]);
	}

	public function innerJoin(Array $join){
		return $this->join(['inner', $join[0], $join[1]]);
	}

	private function join(Array $join){
		$type = $join[0];
		$table = $join[1];
		$clauses = $join[2];
		$allowJoin = ['left', 'right', 'inner', 'full outer'];
		if(!in_array($type, $allowJoin)){
			throw new \Exception("type join: ".$type." not allowed!", 1);
		}
		if($this->join){
			$this->join .= " {$type} join {$table} on {$clauses}";
		}else{
			$this->join = "{$type} join {$table} on {$clauses}";
		}
		return $this;
	}

	public function orderBy($order){
		$this->order = 'order by '.$order;
		return $this;
	}

	public function groupBy($group){
		$this->group = 'group by '.$group;
		return $this;
	}

	public function limit(int $limit){
		$this->limit = 'limit '.$limit;
		return $this;
	}

	public function offset(int $offset){
		$this->offset = 'offset '.$offset;
		return $this;
	}

	public function buildQuery(){
		$this->rawQuery = $this->select;
		if($this->join){
			$this->rawQuery .= ' '.$this->join;
		}
		if($this->where){
			$this->rawQuery .= ' '.$this->where;
		}
		if($this->order){
			$this->rawQuery .= ' '.$this->order;
		}
		if($this->group){
			$this->rawQuery .= ' '.$this->group;
		}
		if($this->limit){
			$this->rawQuery .= ' '.$this->limit;
		}
		if($this->offset){
			$this->rawQuery .= ' '.$this->offset;
		}
		return $this->rawQuery;
	}

	public function rawSql(){
		return $this->buildQuery();
	}

	public function one(){
		$this->limit(1);
		return $this->model->runBuilderQuery($this->buildQuery(), $this->called_class, $one = true);
	}

	public function all(){
		// return $this->buildQuery();
		return $this->model->runBuilderQuery($this->buildQuery(), $this->called_class);
	}

	public function count(){
		$this->select("count({$this->tableName}.*)");
		return $this->model->runBuilderQuery($this->buildQuery(), $this->called_class, $one = false, $selectCount = true);
	}
}