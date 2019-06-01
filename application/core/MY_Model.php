<?php
/**
* @package		:	Codeigniter
* @subpackage	:	Model
* @author 	    :	Musafi'i (musafii.fai@gmail.com)
* @copyright	:	2017
* Defaut parent model class
*/
class MY_Model extends CI_Model
{
	protected $_table;
	protected $_primary_key = "id";

	function __construct($tableName)
	{
		parent::__construct();
		$this->_table = $tableName;
	}

	public function setTable($tbl)
	{
		$this->_table = $tbl;
	}

	public function setPrimaryKey($id)
	{
		$this->_primary_key = $id;
	}

	/**
	* @return for show table
	* @param  $where = array("id" => $id,"name" => $name); // if true
	* @param  $select = "name,title,date"; or array("name","title","date"); // if true
	* @param  $orderBy = "id DESC,name ASC"; or array("id DESC","name ASC"); // if true
	* @param  $search = array("title" => $search,"name" => $search); // if true
	* @param  $all = if(true){ result all }else{ result row };
	* @param  $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* @param  $whereIn = array("id" => array(1,3,3,2,4,5));
	* @param  $limit = integer
	* @param  $offset = integer
	* @param  $groupBy = array("age","salary") or string;
	* @param  $result_array = if(true){ result array() }else{ result object}
	*/
	public function findData($where=false,$select=false,$orderBy=false,$search=false,$join=false,$whereIn=false,$groupBy=false,$all=true,$limit=0,$offset=0,$orWhere=false,$orSearch=false,$result_array = false)
	{
		$this->db->from($this->_table);

		$orWhere == true ? $this->db->or_where($where) : $this->db->where($where);
		if ($select) {
			$select = is_array($select) ? implode(",", $select) : $select;
			$this->db->select($select);
		}
		if ($orderBy) {
		   $orderBy = is_array($orderBy) ? implode(",", $orderBy) : $orderBy;
		   $this->db->order_by($orderBy);
		}

		if ($orSearch) {
			if ($search) {
				if ($where || $whereIn) {
					$this->db->group_start()
							 ->or_like($search)
							 ->group_end();
				} else {
					$this->db->or_like($search);
				}
			}
		} else {
			if ($search) {
				$this->db->like($search);
			}
		}

		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		if ($groupBy) { $this->db->group_by($groupBy); }
		if ($all) {
			if ($limit == 0) {
				$query = $this->db->get();
				/*result_array is true or false*/
				return ($result_array) ? $query->result_array() : $query->result();
			} else {
				$this->db->limit($limit,$offset);
				$query = $this->db->get();
				/*result_array is true or false*/
				return ($result_array) ? $query->result_array() : $query->result();
			}
		} else {
			$query = $this->db->get();
			/*result_array is true or false*/
			return ($result_array) ? $query->row_array() : $query->row();
		}
	}

	/* start pagination self */
	public function findDataPagination($page,$limit=10,$where=false,$select=false,$orderBy=false,$search=false,$join=false,$whereIn=false,$groupBy=false,$orWhere=false,$orSearch=false,$result_array=false)
	{
		$offset = ($page - 1) * $limit;
		return self::findData($where,$select,$orderBy,$search,$join,$whereIn,$groupBy,true,$limit,$offset,$orWhere,$orSearch);
	}

	public function getCountPagination($limit=10,$where=false,$search=false,$join=false,$whereIn=false,$groupBy=false,$orWhere=false,$orSearch=false)
	{
		$resultCount = self::getCount($where,$search,$join,$whereIn,$groupBy,$orWhere,$orSearch);
		$resultCount = ceil($resultCount / $limit);
		return $resultCount;
	}
	/* end pagination self*/

	/**
	*	@return for show table
	*	@param $where = array("id" => $id,"name" => $name); // if true
	*	@param $select = "name,title,date"; or array("name","title","date"); // if true
	*	@param $columnsOrderBy = array(null,"name","age");
	* 	@param $search = array("title" => $search,"name" => $search); // if true
	* 	@param $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* 	@param $whereIn = array("id" => array(1,3,3,2,4,5));
	* 	@param $groupBy = array("age","salary") or string;
	*/
	public function findDataTableObject($where=false,$select=false,$columnsOrderBy=false,$search=false,$join=false,$whereIn=false,$groupBy=false)
	{
		if ($search) {
			$dataSearch = array();
			foreach ($search as $val) {
				$dataSearch[$val] = $this->input->post("search")["value"];
			}
			$search = $dataSearch;
		}
		$result = self::findDataTable($where,$select,$columnsOrderBy,$search,$join,$whereIn,$groupBy);
		$data = array();
		$no = $this->input->post("start");
		foreach ($result as &$item) {
			$no++;
			$item->no = $no;
			$data[] = $item;
		}
		self::findDataTableOutput($data,$where,$select,$search,$join,$whereIn,$groupBy);
	}

	/**
	*	@param $where = array("id" => $id,"name" => $name); // if true
	*	@param $select = "name,title,date"; or array("name","title","date"); // if true
	*	@param $columnsOrderBy = array(null,"name","age");
	* 	@param $search = array("title","name","age"); // if true
	* 	@param $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* 	@param $whereIn = array("id" => array(1,3,3,2,4,5));
	* 	@param $groupBy = array("age","salary") or string;
	*/
	public function findDataTable($where=false,$select=false,$columnsOrderBy=false,$search=false,$join=false,$whereIn=false,$groupBy=false)
	{
		$input = $this->input;
		$orderBy = false;

		if ($columnsOrderBy) {
			if (isset($_POST['order'])) {
				$valColumnName = $columnsOrderBy[$_POST['order']['0']['column']];
				$valKeyword = $_POST['order']['0']['dir'];
				$orderBy = array($valColumnName." ".$valKeyword);
			}
		}
		if ($search) {
			$dataSearch = array();
			foreach ($search as $val) {
				$dataSearch[$val] = $this->input->post("search")["value"];
			}
			$search = $dataSearch;
		}
		$data = $this->findData($where,$select,$orderBy,$search,$join,$whereIn,$groupBy,true,$input->post("length"),$input->post("start"),false,true);
		$no = $this->input->post("start");
		foreach ($data as &$item) {
			$no++;
			$item->no = $no;
		}
		return $data;
	}

	/**
	*	@param $data = result for findDataTablesCore
	*	@param $where = array("id" => $id,"name" => $name); // if true
	*	@param $select = "name,title,date"; or array("name","title","date"); // if true
	*	@param $columnsOrderBy = array(null,"name","age");
	* 	@param $search = array("title" => $search,"name" => $search); // if true
	* 	@param $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* 	@param $whereIn = array("id" => array(1,3,3,2,4,5));
	* 	@param $groupBy = array("age","salary") or string;
	*/
	public function findDataTableOutput($data=null,$where=false,$select=false,$search=false,$join=false,$whereIn=false,$groupBy=false)
	{
		$input = $this->input;
		if ($search) {
			$dataSearch = array();
			foreach ($search as $val) {
				$dataSearch[$val] = $this->input->post("search")["value"];
			}
			$search = $dataSearch;
		}
		$response = new stdClass();

		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $this->getCount($where,$select,$search,$join,$whereIn,$groupBy,false,true);
		// $response->recordsTotal = $this->getCountAll();
		$response->recordsFiltered = $this->getCount($where,$select,$search,$join,$whereIn,$groupBy,false,true);
		$response->data = $data;

		self::json($response);
	}

	private function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$data = isset($data) ? $data : $this->response;
    	$this->output->set_content_type('application/json');
	    $this->output->set_output(json_encode($data));
    	// echo json_encode($data);
	}

	/**
	* @return for option select or combobox data
	* @param $where = array("id" => $id,"name" => $name); // if true
	* @param $select = "name,title,date"; or array("name","title","date"); // if true
	* @param $orderBy = "id DESC,name ASC"; or array("id DESC","name ASC"); // if true
	* @param $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* @param $whereIn = array("id" => array(1,3,3,2,4,5));
	* @param $groupBy = array("age","salary") or string;
	* @param $result_array = if(true){ result array() }else{ result object}
	*/
	public function getAll($where=false,$select=false,$orderBy=false,$join=false,$whereIn=false,$groupBy=false,$result_array=false)
	{
		if ($where) {
			$this->db->where($where);
		}
		if ($select) {
			$select = is_array($select) ? implode(",", $select) : $select;
			$this->db->select($select);
		}
		if ($orderBy) {
		   $orderBy = is_array($orderBy) ? implode(",", $orderBy) : $orderBy;
		   $this->db->order_by($orderBy);
		}
		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		if ($groupBy) { $this->db->group_by($groupBy); }
		$query = $this->db->get($this->_table);
		if ($result_array) {
			return $query->result_array();
		} else {
			return $query->result();
		}
	}

	/**
	* @param $where = array("id" => $id,"name" => $name); // if true
	* @param $select = "name,title,date"; or array("name","title","date"); // if true
	* @param $search = array("title" => $search,"name" => $search); // if true
	* @param $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* @param $whereIn = array("id" => array(1,3,3,2,4,5));
	*/
	public function getCount($where=false,$select=false,$search=false,$join=false,$whereIn=false,$groupBy=false,$orWhere=false,$orSearch=false)
	{
		$this->db->from($this->_table);
		if ($select) {
			$select = is_array($select) ? implode(",", $select) : $select;
			$this->db->select($select);
		}
		if($orWhere) {
			$this->db->or_where($where);
		} else {
			$this->db->where($where);
		}
		if ($orSearch) {
			if ($search) {
				if ($where || $whereIn) {
					$this->db->group_start()
							 ->or_like($search)
							 ->group_end();
				} else {
					$this->db->or_like($search);
				}
			}
		} else {
			if ($search) {
				$this->db->like($search);
			}
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		}
		if ($groupBy) { $this->db->group_by($groupBy); }
		return $this->db->count_all_results();
	}

	public function getCountAll()
	{
		$this->db->from($this->_table);
		return $this->db->count_all_results();
	}


	/**
	* @param $select = "name";
	* @param $where = array("id" => $id,"name" => $name); // if true
	* @param $row_array = if(true){ row array() }else{ row object}
	*/
	public function getSelectSum($select,$where=false,$row_array=false)
	{
		$this->db->select_sum($select);
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
		return $query;
	}

	public function getRoles()
	{
		$result = $this->db->get_where("user_roles",array("role !=" => "dev"));
		return $result->result();
	}

	/**
	* @param $id = for url $this->uri->segment(3); // example
	* @param $select = "name,title,date"; or array("name","title","date"); // if true
	* @param $row_array = if(true){ row array() }else{ row object}
	*/
	public function getById($id,$select = false,$row_array = false)
	{
		$this->db->where($this->_primary_key,$id);
		if ($select) {
			if (is_array($select)) {
				$this->db->select(implode(",", $select));
			} else {
				$this->db->select($select);
			}
		}

		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	/**
	* @param $where = array("id" => $id,"name" => $name);
	* @param $select = "name,title,date"; or array("name","title","date"); // if true
	* @param $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* @param $row_array = if(true){ row array() }else{ row object}
	*/
	public function getByWhere($where,$select = false,$join=false,$row_array = false)
	{
		$this->db->where($where);
		if ($select) {
			if (is_array($select)) {
				$this->db->select(implode(",", $select));
			} else {
				$this->db->select($select);
			}
		}
		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		}
		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	/**
	* @param $data = array("name" => $name,"age" => $age);
	*/
	public function insert($data)
	{
		if ($this->db->insert($this->_table,$data)) {
			return $this->db->insert_id();
		}
	}

	/**
	* @param $data = array("id" => $id,"name" => $name);
	*/
	public function update($data)
	{
		$this->db->where($this->_primary_key,$data[$this->_primary_key]);
		return $this->db->update($this->_table,$data);
	}

	/**
	* @param $where = array("id" => $id,"name" => $name);
	* @param $data = array(name" => $name,"age" => $age);
	*/
	public function updateWhere($where,$data)
	{
		$this->db->where($where);
		return $this->db->update($this->_table,$data);
	}

	/**
	* @param $id = for url $this->uri->segment(3); // example
	*/
	public function delete($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->delete($this->_table);
		return $this->db->affected_rows();
	}

	/**
	* @param $where = array("id" => $id,"name" => $name);
	*/
	public function deleteWhere($where)
	{
		$this->db->where($where);
		$this->db->delete($this->_table);
		return $this->db->affected_rows();
	}

	/**
	* @param $queryAll = array($this->db->insert(),$this->db->insert(),$this->db->update(),$this->db->delete())
	*/
	public function transactionMerge($queryAll)
	{
		$this->db->trans_start();
		// $this->db->query('AN SQL QUERY...');
		// $this->db->query('ANOTHER QUERY...');
		// $this->db->query('AND YET ANOTHER QUERY...');

		// for ($queryAll=0; $queryAll < count($queryAll); $queryAll++) {
		// 	$queryAll;
		// }

		foreach ($queryAll as $value) {
			$value;
		}

		return $this->db->trans_complete();
	}

	/**
	* @param $queryAll = array($this->db->insert(),$this->db->insert(),$this->db->update(),$this->db->delete())
	*/
	public function transactionBeginMerge($queryAll)
	{
		$this->db->trans_begin();

		/*for ($queryAll=0; $queryAll < count($queryAll); $queryAll++) {
			$queryAll;
		}*/

		foreach ($queryAll as $value) {
			$value;
		}

		// $this->db->query('AN SQL QUERY...');
		// $this->db->query('ANOTHER QUERY...');
		// $this->db->query('AND YET ANOTHER QUERY...');

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		} else {
		    $this->db->trans_commit();
		    return true;
		}
	}
}
