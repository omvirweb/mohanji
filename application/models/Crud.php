<?php

/**
 * Class Crud
 * &@property CI_DB_active_record $db
 */
class Crud extends CI_Model
{
	function __construct()
	{
		parent::__construct();
        $this->today_date = date('Y-m-d');
	}

	/**
	 * @param $table_name
	 * @param $data_array
	 * @return bool
	 */
	function insert($table_name,$data_array){
		if($this->db->insert($table_name,$data_array))
		{
			return $this->db->insert_id();
		}
		return false;
	}

	function insert_csv($data,$table) {
		$this->db->insert($table, $data);
	}

	function insertFromSql($sql)
	{
		$this->db->query($sql);
		return $this->db->insert_id();
	}

	function execuetSQL($sql){
		$this->db->query($sql);
	}
	function getFromSQL($sql)
	{
		return $this->db->query($sql)->result();
	}
        function getFromSQLArray($sql)
	{
		return $this->db->query($sql)->result_array();
	}
    
    function getFromSQL_procedure($sql)
	{
		$sql_p = $this->db->query($sql);
        return $sql_p;
	}

	/**
	 * @param $table_name
	 * @param $order_by_column
	 * @param $order_by_value
	 * @return bool
	 */
	function get_all_records($table_name,$order_by_column,$order_by_value){
		$this->db->select("*");
		$this->db->from($table_name);
		$this->db->order_by($order_by_column,$order_by_value);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}else{
			return false;
		}
	}

	/**
	 * @param $table_name
	 * @param $order_by_column
	 * @param $order_by_value
	 * @param $where_array
	 * @return bool
	 */
	function get_all_with_where($table_name,$order_by_column,$order_by_value,$where_array)
	{
		$this->db->select("*");
		$this->db->from($table_name);
		$this->db->where($where_array);
		$this->db->order_by($order_by_column,$order_by_value);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}else{
			return false;
		}
	}

	/**
	 * @param $tbl_name
	 * @param $column_name
	 * @param $where_id
	 * @return mixed
	 */
	function get_column_value_by_id($tbl_name,$column_name,$where_id)
	{				
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where_id);		
		$this->db->last_query();
		$query = $this->db->get();
		return $query->row($column_name);
	}

	/**
	 * @param $table_name
	 * @param $where_id
	 * @return mixed
	 */
	function get_row_by_id($table_name,$where_id){
		$this->db->select("*");
		$this->db->from($table_name);
		$this->db->where($where_id);
		$query = $this->db->get();
		return $query->result();
	}

        function get_row_by_where($table_name,$where){
		$this->db->select("*");
		$this->db->from($table_name);
		$this->db->where($where);
                $query = $this->db->get();
		return $query->row();
	}

	/**
	 * @param $table_name
	 * @param $where_array
	 * @return mixed
	 */
	function delete($table_name,$where_array){		
		$result = $this->db->delete($table_name,$where_array);
                $return = array();
                if ($result == '') {
                    $return['error'] = "Error";
                }
                else {
                    $return['success'] = 'Deleted';
                }
		return $return;
	}
	
	/**
	 * @param $table_name
	 * @param $where_id
	 * @param $where_in_array
	 * @return mixed
	 */
	function delete_where_in($table_name, $where_id, $where_in_array){		
		$this->db->where_in($where_id, $where_in_array);
		$result = $this->db->delete($table_name);
		return $result;
	}

	/**
	 * @param $table_name
	 * @param $data_array
	 * @param $where_array
	 * @return mixed
	 */
	function update($table_name,$data_array,$where_array){
		$this->db->where($where_array);
		$rs = $this->db->update($table_name, $data_array);
		return $rs;
	}

	/**
	 * @param $name
	 * @param $path
	 * @return bool
	 */
	function upload_file($name, $path)
	{
		$config['upload_path'] = $path;
		$config ['allowed_types'] = '*';
        $this->load->library('upload');
		$this->upload->initialize($config);
		if($this->upload->do_upload($name))
		{
			$upload_data = $this->upload->data();
			return $upload_data['file_name'];
		}
		return false;
	}

	/**
	 * @param $table
	 * @param $id_column
	 * @param $column
	 * @param $column_val
	 * @return null
	 */
	function get_id_by_val($table,$id_column,$column,$column_val){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($column,$column_val);
		$this->db->limit('1');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}
	function get_val_by_id($table,$id_column,$column,$column_val){
		$this->db->select($column_val);
		$this->db->from($table);
		$this->db->where($column,$id_column);
		$this->db->limit('1');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$column_val;
		} else {
			return null;
		}
	}

	function get_id_by_val_count($table,$id_column,$where_array){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($where_array);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->num_rows();
		} else {
			return null;
		}
	}
	
	function get_id_by_val_not($table,$id_column,$column,$column_val,$permalink){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($column,$column_val);
		$this->db->where_not_in($id_column, $permalink);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}
	
	function get_same_by_val($table,$id_column,$column1,$column1_val,$column2,$column2_val,$id = null){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($column1,$column1_val);
		$this->db->where($column2,$column2_val);
		$this->db->where($id_column."!=",$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}

	function get_same_by_multi_where($table,$id_column,$column1,$column1_val,$column2,$column2_val,$column3,$column3_val,$id = null){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($column1,$column1_val);
		$this->db->where($column2,$column2_val);
		$this->db->where($column3,$column3_val);
		$this->db->where($id_column."!=",$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}
	
	function limit_words($string, $word_limit=30){
		$words = explode(" ",$string);
		return implode(" ", array_splice($words, 0, $word_limit));
	}
	function limit_character($string, $character_limit=30){
		if (strlen($string) > $character_limit) {
			return substr($string, 0, $character_limit).'...';
		}else{
			return $string;
		}
	}
	//select data 
	function get_select_data($tbl_name)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$query = $this->db->get();
		return $query->result();
	}

	// Select data For specific Columns
	// $columns Array
	function get_specific_column_data($tbl_name, $columns)
	{
		$columns = implode(', ', $columns);
		$this->db->select($columns);
		$this->db->from($tbl_name);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * @param $tbl_name
	 * @param $where
	 * @param $where_id
	 * @return mixed
	 */
	function get_data_row_by_id($tbl_name,$where,$where_id)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where,$where_id);
		$query = $this->db->get();
		return $query->row();
	}
	function get_result_where($tbl_name,$where,$where_id)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where,$where_id);
		$query = $this->db->get();
		return $query->result();
	}

	function get_where_in_result($tbl_name,$where,$where_in)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where_in($where,$where_in);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	function getUserChatRoleIDS($user_id)
	{
		$array = array();
		$sql = "SELECT * FROM chat_roles WHERE staff_id='$user_id'";
		$rows = $this->db->query($sql)->result();        
		$i = 0;        
		foreach($rows as $row)
		{
			$array[$i] = $row->allowed_staff_id;
			$i++;        
		}    
		return $array;
	}
	
	function get_max_number($tbl_name,$column_name)
	{
		$this->db->select_max($column_name);
		$result = $this->db->get($tbl_name)->row();  
		return $result;
	}

        function get_max_number_where($tbl_name,$column_name,$where_array)
	{
		$this->db->select_max($column_name);
                $this->db->where($where_array);
		$result = $this->db->get($tbl_name)->row();
		return $result;
	}
    
    function get_min_value($tbl_name,$column_name){
		$this->db->select_min($column_name);
		$result = $this->db->get($tbl_name)->row();
		return $result;
	}
    
    function get_last_record_where($tbl_name, $column_name, $order_by, $where_array) {
        $this->db->select($column_name);
        $this->db->where($where_array);
        $this->db->order_by($order_by, "desc");
        $this->db->limit(1);
        $result = $this->db->get($tbl_name)->row();
        return $result;
    }

	public function get_columns_val_by_where($table_name,$column_name,$where_array)
	{
		$this->db->select($column_name);
		$this->db->from($table_name);
		$this->db->where($where_array);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	public function get_column_value_implode($table_name,$column_name){
		$this->db->select($column_name);
		$this->db->from($table_name);
		$this->db->group_by($column_name);
		$this->db->where($column_name.' IS NOT NULL', null, false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			//return $query->result();
			foreach($query->result() as $row){
				$ids[] = $row->$column_name;
			}
			return implode(",",$ids);
		}else{
			return false;
		}
	}
	
	function get_column_data_array($table_name,$column_name)
    {
	$array = array();
        $sql = "SELECT ".$column_name." FROM ".$table_name." GROUP BY ".$column_name." ORDER BY ".$column_name." ASC";
        $rows = $this->db->query($sql)->result();
        $i = 0;
        foreach ($rows as $row) {
            $array[] = $row->$column_name;
            $i++;
        }
        return $array;
    }
    
    function get_order_status_id($order_status_id){
        $query= $this->db->query("CALL `update_order_status_id`(".$order_status_id.")");
        $data = $query->result();
        $query->free_result();
        return $data; 
    }
    
    function get_row_by_order($table, $where_array, $order_by_column, $order_by_value) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where_array);
        $this->db->order_by($order_by_column, $order_by_value);
        $this->db->limit('1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    function get_row_for_customer_ledger($account_id,$from_date,$to_date) {
        $this->db->select('s.sell_id,s.account_id,s.sell_no,s.sell_date');
        $this->db->from('sell s');
        $this->db->where('s.account_id',$account_id);
        $this->db->where('s.sell_date >=',$from_date);
        $this->db->where('s.sell_date <=',$to_date);
        //$this->db->order_by($order_by_column, $order_by_value);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function get_sell_items($sell_id) {
        $this->db->select("`st`.`type_name`, `c`.`category_name`, `i`.`item_name`, `si`.`grwt`, `si`.`less`, `si`.`net_wt`, `si`.`touch_id`, IF(c.category_group_id = 1, IF(si.type = 1,`si`.`fine`,CONCAT('-',`si`.`fine`)), '') AS gold_fine, IF(c.category_group_id = 1, '', IF(si.type = 1,`si`.`fine`,CONCAT('-',`si`.`fine`))) AS silver_fine");
        $this->db->from('sell_items si');
        $this->db->join('sell_type st', 'st.sell_type_id = si.type', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master i', 'i.item_id = si.item_id', 'left');
        $this->db->where('si.sell_id',$sell_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function get_payment_receipt($sell_id) {
        $this->db->select("IF(p.payment_receipt = 1, 'Payment', 'Receipt') AS pay_rec, IF(p.cash_cheque = 1, 'Cash', a.bank_name) AS cash_cheque, IF(p.payment_receipt = 1,`p`.`amount`,CONCAT('-',`p`.`amount`)) AS amount");
        $this->db->from('payment_receipt p');
        $this->db->join('account a', 'a.account_id = p.bank_id', 'left');
        $this->db->where('p.sell_id',$sell_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function get_metal_payment_receipt($sell_id) {
        $this->db->select("IF(p.metal_payment_receipt = 1, 'Payment', 'Receipt') AS pay_rec, `c`.`category_name`, `i`.`item_name`, `p`.`metal_grwt`, '0' AS less, `p`.`metal_ntwt` AS `ntwt`, `p`.`metal_tunch`, IF(c.category_group_id = 1, IF(p.metal_payment_receipt = 1,`p`.`metal_fine`,CONCAT('-',`p`.`metal_fine`)), '') AS gold_fine, IF(c.category_group_id = 1, '', IF(p.metal_payment_receipt = 1,`p`.`metal_fine`,CONCAT('-',`p`.`metal_fine`))) AS silver_fine");
        $this->db->from('metal_payment_receipt p');
        $this->db->join('category c', 'c.category_id = p.metal_category_id', 'left');
        $this->db->join('item_master i', 'i.item_id = p.metal_item_id', 'left');
        $this->db->where('p.sell_id',$sell_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function get_gold_bhav($sell_id) {
        $this->db->select("IF(g.gold_sale_purchase = 1, 'Sell', 'Purchase') AS sell_purchase,IF(g.gold_sale_purchase = 1, CONCAT('-',g.gold_weight), g.gold_weight) AS gold_weight,g.gold_rate,IF(g.gold_sale_purchase = 1, g.gold_value, CONCAT('-',g.gold_value)) AS gold_value");
        $this->db->from('gold_bhav g');
        $this->db->where('g.sell_id',$sell_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function get_silver_bhav($sell_id) {
        $this->db->select("IF(s.silver_sale_purchase = 1, 'Sell', 'Purchase') AS sell_purchase,IF(s.silver_sale_purchase = 1, CONCAT('-',s.silver_weight), s.silver_weight) AS silver_weight,s.silver_rate,IF(s.silver_sale_purchase = 1, s.silver_value, CONCAT('-',s.silver_value)) AS silver_value");
        $this->db->from('silver_bhav s');
        $this->db->where('s.sell_id',$sell_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function get_transaction($sell_id) {
        $this->db->select("a.account_name,IF(t.naam_jama = 1, 'Naam(Dr)', 'Jama(Cr)') AS naam_jama,IF(t.naam_jama = 1, CONCAT('-',t.transfer_gold), t.transfer_gold) AS gold_fine,IF(t.naam_jama = 1, CONCAT('-',t.transfer_silver), t.transfer_silver) AS silver_fine,IF(t.naam_jama = 1, CONCAT('-',t.transfer_amount), t.transfer_amount) AS amount");
        $this->db->from('transfer t');
        $this->db->join('account a', 'a.account_id = t.transfer_account_id', 'left');
        $this->db->where('t.sell_id',$sell_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    function get_account_opening_balance($account_id,$from_date){
        $this->db->select("SUM(s.total_gold_fine) AS total_gold, SUM(s.total_silver_fine) AS total_silver, SUM(s.total_amount) AS total_amount");
        $this->db->from('sell s');
        $this->db->where('s.account_id', $account_id);
        $this->db->where('s.sell_date <', $from_date);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    function get_purchase_to_sell_items($department_id, $item_id) {

        $this->db->select("i.*, 'Opening Stock' AS account_name, c.category_name, '' as account_id, os.opening_stock_id as sell_item_id, os.opening_stock_id, '' AS reference_no, '' AS type_id, i.category_id, i.item_id, i.grwt, i.less, i.ntwt AS net_wt, i.tunch AS touch_id, i.fine, i.purchase_sell_item_id, '' AS sell_date ,i.stock_type, '0' AS wstg, '' AS image, os.design_no, os.rfid_number");
        $this->db->from('item_stock i');
        $this->db->join('opening_stock os', 'os.opening_stock_id = i.purchase_sell_item_id', 'left');
        $this->db->join('category c', 'c.category_id = os.category_id', 'left');
        $this->db->where('i.department_id', $department_id);
        $this->db->where('i.item_id', $item_id);
        $this->db->where('i.stock_type', STOCK_TYPE_OPENING_STOCK_ID);
        $query = $this->db->get();
        $o_result1 = $query->result();
//        $result = $query->result();

        $this->db->select("i.*, 'Opening Stock' AS account_name, c.category_name, '' as account_id, os.opening_stock_id as sell_item_id, os.opening_stock_id, '' AS reference_no, '' AS type_id, i.category_id, i.item_id, i.grwt, i.less, i.ntwt AS net_wt, i.tunch AS touch_id, i.fine, i.purchase_sell_item_id, '' AS sell_date ,i.stock_type, '0' AS wstg, '' AS image, os.design_no, os.rfid_number");
        $this->db->from('item_stock i');
        $this->db->join('opening_stock os', 'os.purchase_sell_item_id = i.purchase_sell_item_id', 'left');
        $this->db->join('category c', 'c.category_id = os.category_id', 'left');
        $this->db->where('i.department_id', $department_id);
        $this->db->where('i.item_id', $item_id);
        $this->db->where_in('os.stock_type', array(STOCK_TYPE_PURCHASE_ID, STOCK_TYPE_EXCHANGE_ID, STOCK_TYPE_STOCK_TRANSFER_ID, STOCK_TYPE_IR_RECEIVE_ID, STOCK_TYPE_MHM_RECEIVE_FINISH_ID, STOCK_TYPE_MHM_RECEIVE_SCRAP_ID, STOCK_TYPE_MC_RECEIVE_FINISH_ID, STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
        $this->db->where('i.grwt !=', '0');
        $this->db->group_by('i.item_stock_id');
        $query = $this->db->get();
        $o_result2 = $query->result();
//        echo '<pre>'. $this->db->last_query(); exit;
        $result = array_merge($o_result1, $o_result2);

        $this->db->select("si.sell_item_id, si.sell_id, si.sell_item_no, si.type, is.category_id, is.item_id, is.grwt, is.less, is.ntwt AS net_wt, is.tunch AS touch_id, is.fine, is.purchase_sell_item_id, si.image, c.category_name, a.account_name, s.account_id, s.sell_date, is.stock_type, si.wstg");
//        $this->db->select("si.sell_item_id, si.sell_id, si.sell_item_no, si.type, si.category_id, si.item_id, si.grwt, si.less, si.net_wt, si.touch_id, si.fine, is.purchase_sell_item_id, si.image, c.category_name, a.account_name, s.account_id, s.sell_date, is.stock_type, si.wstg");
        $this->db->from('item_stock is');
        $this->db->join('sell_items si', 'si.sell_item_id = is.purchase_sell_item_id', 'left');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->where('is.department_id', $department_id);
        $this->db->where('is.item_id', $item_id);
        $this->db->where('si.type != ', SELL_TYPE_SELL_ID);
        $this->db->where_in('is.stock_type', array(STOCK_TYPE_PURCHASE_ID, STOCK_TYPE_EXCHANGE_ID));
        $query1 = $this->db->get();
        $result1 = $query1->result();
        
//        echo '<pre>'. $this->db->last_query(); exit;
        $this->db->select("i.*, a.account_name, c.category_name, ir.worker_id as account_id, ird.ird_id as sell_item_id, ir.ir_id, ir.reference_no, ird.type_id, i.category_id, i.item_id, i.grwt, i.less, i.ntwt AS net_wt, i.tunch AS touch_id, i.fine, i.purchase_sell_item_id, ird.ird_date AS sell_date ,i.stock_type, '0' AS wstg, '' AS image");
        $this->db->from('item_stock i');
        $this->db->join('issue_receive_details ird', 'ird.ird_id = i.purchase_sell_item_id', 'left');
        $this->db->join('issue_receive ir', 'ir.ir_id=ird.ir_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.worker_id', 'left');
        $this->db->join('category c', 'c.category_id = ird.category_id', 'left');
        $this->db->where('i.department_id', $department_id);
        $this->db->where('i.item_id', $item_id);
        $this->db->where('i.stock_type', STOCK_TYPE_IR_RECEIVE_ID);
        $this->db->where('ird.type_id != ', MANUFACTURE_TYPE_ISSUE_ID);
        $query2 = $this->db->get();
        $result2 = $query2->result();
//        echo '<pre>'. $this->db->last_query(); exit;
        
        $this->db->select("i.*, 'Stock Transfer' AS account_name, c.category_name, '' as account_id, std.transfer_detail_id as sell_item_id, st.stock_transfer_id, '' AS reference_no, '' AS type_id, i.category_id, i.item_id, i.grwt, i.less, i.ntwt AS net_wt, i.tunch AS touch_id, i.fine, i.purchase_sell_item_id, st.transfer_date AS sell_date ,i.stock_type, '0' AS wstg, '' AS image");
        $this->db->from('item_stock i');
        $this->db->join('stock_transfer_detail std', 'std.transfer_detail_id = i.purchase_sell_item_id', 'left');
        $this->db->join('stock_transfer st', 'st.stock_transfer_id = std.stock_transfer_id', 'left');
        $this->db->join('category c', 'c.category_id = std.category_id', 'left');
        $this->db->where('i.department_id', $department_id);
        $this->db->where('i.item_id', $item_id);
        $this->db->where('i.stock_type', STOCK_TYPE_STOCK_TRANSFER_ID);
        $query3 = $this->db->get();
        $result3 = $query3->result();
        
        $this->db->select("i.*, a.account_name, c.category_name, mhm.worker_id as account_id, mhm_detail.mhm_detail_id as sell_item_id, mhm.mhm_id, mhm.reference_no, mhm_detail.type_id, i.category_id, i.item_id, i.grwt, i.less, i.ntwt AS net_wt, i.tunch AS touch_id, i.fine, i.purchase_sell_item_id, mhm_detail.mhm_detail_date AS sell_date ,i.stock_type, '0' AS wstg, '' AS image");
        $this->db->from('item_stock i');
        $this->db->join('manu_hand_made_details mhm_detail', 'mhm_detail.mhm_detail_id = i.purchase_sell_item_id', 'left');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id=mhm_detail.mhm_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.worker_id', 'left');
        $this->db->join('category c', 'c.category_id = mhm_detail.category_id', 'left');
        $this->db->where('i.department_id', $department_id);
        $this->db->where('i.item_id', $item_id);
        $this->db->where_in('i.stock_type', array(STOCK_TYPE_MHM_RECEIVE_FINISH_ID, STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
        $this->db->where('mhm_detail.type_id != ', MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID);
        $this->db->where('mhm_detail.type_id != ', MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID);
        $query4 = $this->db->get();
        $result4 = $query4->result();
//        echo '<pre>'. $this->db->last_query(); exit;
        
        $this->db->select("i.*, a.account_name, c.category_name, machine_chain.worker_id as account_id, machine_chain_detail.machine_chain_detail_id as sell_item_id, machine_chain.machine_chain_id, machine_chain.reference_no, machine_chain_detail.type_id, i.category_id, i.item_id, i.grwt, i.less, i.ntwt AS net_wt, i.tunch AS touch_id, i.fine, i.purchase_sell_item_id, machine_chain_detail.machine_chain_detail_date AS sell_date ,i.stock_type, '0' AS wstg, '' AS image");
        $this->db->from('item_stock i');
        $this->db->join('machine_chain_details machine_chain_detail', 'machine_chain_detail.machine_chain_detail_id = i.purchase_sell_item_id', 'left');
        $this->db->join('machine_chain machine_chain', 'machine_chain.machine_chain_id=machine_chain_detail.machine_chain_id', 'left');
        $this->db->join('account a', 'a.account_id = machine_chain.worker_id', 'left');
        $this->db->join('category c', 'c.category_id = machine_chain_detail.category_id', 'left');
        $this->db->where('i.department_id', $department_id);
        $this->db->where('i.item_id', $item_id);
        $this->db->where_in('i.stock_type', array(STOCK_TYPE_MC_RECEIVE_FINISH_ID, STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
        $this->db->where('machine_chain_detail.type_id != ', MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID);
        $this->db->where('machine_chain_detail.type_id != ', MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID);
        $query5 = $this->db->get();
        $result5 = $query5->result();
//        echo '<pre>'. $this->db->last_query(); exit;
        
        $result_array = array_merge($result, $result1, $result2, $result3, $result4, $result5);
        if ($result_array > 0) {
            return $result_array;
        } else {
            return null;
        }
    }
    
    
    function get_pts_total_grwt_less($sell_item_id, $stock_type, $old_sell_item_ids){
        $this->db->select("SUM(grwt) AS total_grwt, SUM(less) AS total_less");
        $this->db->from('sell_items');
        $this->db->where('purchase_sell_item_id', $sell_item_id);
        $this->db->where('stock_type', $stock_type);
        if(!empty($old_sell_item_ids)){
            $this->db->where_in('sell_item_id', $old_sell_item_ids);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    function get_ptst_total_grwt_less($sell_item_id, $stock_type, $old_stock_transfer_detail_ids){
        $this->db->select("SUM(grwt) AS total_grwt, SUM(less) AS total_less");
        $this->db->from('stock_transfer_detail');
        $this->db->where('purchase_sell_item_id', $sell_item_id);
        $this->db->where('stock_type', $stock_type);
        if(!empty($old_stock_transfer_detail_ids)){
            $this->db->where_in('transfer_detail_id', $old_stock_transfer_detail_ids);
        }
//        echo $this->db->last_query();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    function get_ptir_total_grwt_less($sell_item_id, $stock_type, $old_issue_receive_item_ids){
        $this->db->select("SUM(weight) AS total_grwt, SUM(less) AS total_less");
        $this->db->from('issue_receive_details');
        $this->db->where('purchase_sell_item_id', $sell_item_id);
        $this->db->where('stock_type', $stock_type);
        if(!empty($old_issue_receive_item_ids)){
            $this->db->where_in('ird_id', $old_issue_receive_item_ids);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    function get_ptmhm_total_grwt_less($sell_item_id, $stock_type, $old_manu_hand_made_item_ids){
        $this->db->select("SUM(weight) AS total_grwt, SUM(less) AS total_less");
        $this->db->from('manu_hand_made_details');
        $this->db->where('purchase_sell_item_id', $sell_item_id);
        $this->db->where('stock_type', $stock_type);
        if(!empty($old_manu_hand_made_item_ids)){
            $this->db->where_in('mhm_detail_id', $old_manu_hand_made_item_ids);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    function get_ptmc_total_grwt_less($sell_item_id, $stock_type, $old_machine_chain_item_ids){
        $this->db->select("SUM(weight) AS total_grwt, SUM(less) AS total_less");
        $this->db->from('machine_chain_details');
        $this->db->where('purchase_sell_item_id', $sell_item_id);
        $this->db->where('stock_type', $stock_type);
        if(!empty($old_machine_chain_item_ids)){
            $this->db->where_in('machine_chain_detail_id', $old_machine_chain_item_ids);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    function get_sell_items_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("si.*, s.sell_date as st_date, a.account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort");
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('s.process_id', $department_ids);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('si.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('si.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('si.touch_id', $tunch);
        }
        if($type_sort == 'P'){
            $this->db->where('si.type', SELL_TYPE_PURCHASE_ID);
        }
        if($type_sort == 'S'){
            $this->db->where('si.type', SELL_TYPE_SELL_ID);
        }
        if($type_sort == 'E'){
            $this->db->where('si.type', SELL_TYPE_EXCHANGE_ID);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_metal_payment_receipt_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("mpr.*, mpr.metal_item_id as item_id, mpr.metal_grwt as grwt, mpr.metal_tunch as touch_id, 'less', mpr.metal_ntwt as net_wt, 'wstg', mpr.metal_fine as fine, s.sell_date as st_date, a.account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort");
        $this->db->from('metal_payment_receipt mpr');
        $this->db->join('sell s', 's.sell_id = mpr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('s.process_id', $department_ids);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('mpr.metal_category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mpr.metal_item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('mpr.metal_tunch', $tunch);
        }
        if($type_sort == 'M R'){
            $this->db->where('mpr.metal_payment_receipt', '2');
        }
        if($type_sort == 'M P'){
            $this->db->where('mpr.metal_payment_receipt', '1');
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_stock_transfer_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("std.*, std.tunch as touch_id, std.ntwt as net_wt, st.transfer_date as st_date, a.account_name, st.stock_transfer_id as st_id, '". $type_sort ."' as type_sort");
        $this->db->from('stock_transfer_detail std');
        $this->db->join('stock_transfer st', 'st.stock_transfer_id = std.stock_transfer_id', 'left');
        if($type_sort == 'F T'){
            $this->db->join('account a', 'a.account_id = st.to_department', 'left');
        }
        if($type_sort == 'T T'){
            $this->db->join('account a', 'a.account_id = st.from_department', 'left');
        }
        if(!empty($to_date)){
            $this->db->where('st.transfer_date >=',$from_date);
            $this->db->where('st.transfer_date <=',$to_date);
        } else {
            $this->db->where('st.transfer_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if($type_sort == 'F T'){
            if(!empty($department_ids)){
                $this->db->where_in('st.from_department', $department_ids);
            }
            if(!empty($department_id)){
                $this->db->where('st.from_department', $department_id);
            }
        }
        if($type_sort == 'T T'){
            if(!empty($department_ids)){
                $this->db->where_in('st.to_department', $department_ids);
            }
            if(!empty($department_id)){
                $this->db->where('st.to_department', $department_id);
            }
        }
        if(!empty($category_id)){
            $this->db->where('std.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('std.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('std.tunch', $tunch);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_issue_receive_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("ird.*, ird.weight as grwt, ird.tunch as touch_id, 0 AS wstg, ird.ird_date as st_date, a.account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, ir.hisab_done");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('ird.ird_date >=',$from_date);
            $this->db->where('ird.ird_date <=',$to_date);
        } else {
            $this->db->where('ird.ird_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('ir.department_id', $department_ids);
        }
        if(!empty($account_id)){
            $this->db->where('ir.worker_id', $account_id);
        }
        if(!empty($department_id)){
            $this->db->where('ir.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('ird.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ird.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('ird.tunch', $tunch);
        }
        if($type_sort == 'MFI'){
            $this->db->where('ird.type_id', MANUFACTURE_TYPE_ISSUE_ID);
        }
        if($type_sort == 'MFR'){
            $this->db->where('ird.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_issue_receive_silver_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("irsd.*, irsd.weight as grwt, irsd.tunch as touch_id, 0 AS wstg, irsd.irsd_date as st_date, a.account_name, irs.irs_id as st_id, '". $type_sort ."' as type_sort, irs.hisab_done");
        $this->db->from('issue_receive_silver_details irsd');
        $this->db->join('issue_receive_silver irs', 'irs.irs_id = irsd.irs_id', 'left');
        $this->db->join('account a', 'a.account_id = irs.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('irsd.irsd_date >=',$from_date);
            $this->db->where('irsd.irsd_date <=',$to_date);
        } else {
            $this->db->where('irsd.irsd_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('irs.department_id', $department_ids);
        }
        if(!empty($account_id)){
            $this->db->where('irs.worker_id', $account_id);
        }
        if(!empty($department_id)){
            $this->db->where('irs.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('irsd.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('irsd.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('irsd.tunch', $tunch);
        }
        if($type_sort == 'MFIS'){
            $this->db->where('irsd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
        }
        if($type_sort == 'MFRS'){
            $this->db->where('irsd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("mhm_detail.*, mhm_detail.weight as grwt, mhm_detail.tunch as touch_id, 0 AS wstg, mhm_detail.mhm_detail_date as st_date, a.account_name, mhm.mhm_id as st_id, '". $type_sort ."' as type_sort, mhm.hisab_done");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('mhm_detail.mhm_detail_date >=',$from_date);
            $this->db->where('mhm_detail.mhm_detail_date <=',$to_date);
        } else {
            $this->db->where('mhm_detail.mhm_detail_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('mhm.department_id', $department_ids);
        }
        if(!empty($account_id)){
            $this->db->where('mhm.worker_id', $account_id);
        }
        if(!empty($department_id)){
            $this->db->where('mhm.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('mhm_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mhm_detail.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('mhm_detail.tunch', $tunch);
        }
        if($type_sort == 'MHMIFW'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'MHMIS'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'MHMRFW'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'MHMRS'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_casting_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("ce_detail.*, ce_detail.weight as grwt, ce_detail.tunch as touch_id, 0 AS wstg, ce_detail.ce_detail_date as st_date, a.account_name, ce.ce_id as st_id, '". $type_sort ."' as type_sort, ce.hisab_done");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->join('account a', 'a.account_id = ce.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('ce_detail.ce_detail_date >=',$from_date);
            $this->db->where('ce_detail.ce_detail_date <=',$to_date);
        } else {
            $this->db->where('ce_detail.ce_detail_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('ce.department_id', $department_ids);
        }
        if(!empty($account_id)){
            $this->db->where('ce.worker_id', $account_id);
        }
        if(!empty($department_id)){
            $this->db->where('ce.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('ce_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ce_detail.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('ce_detail.tunch', $tunch);
        }
        if($type_sort == 'CASTINGIFW'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'CASTINGIS'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'CASTINGRFW'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'CASTINGRS'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        $this->db->select("mc_detail.*, mc_detail.weight as grwt, mc_detail.tunch as touch_id, 0 AS wstg, mc_detail.machine_chain_detail_date as st_date, a.account_name, mc.machine_chain_id as st_id, '". $type_sort ."' as type_sort, mc.hisab_done");
        $this->db->from('machine_chain_details mc_detail');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mc_detail.machine_chain_id', 'left');
        $this->db->join('account a', 'a.account_id = mc.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('mc_detail.machine_chain_detail_date >=',$from_date);
            $this->db->where('mc_detail.machine_chain_detail_date <=',$to_date);
        } else {
            $this->db->where('mc_detail.machine_chain_detail_date <',$from_date);
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('mc.department_id', $department_ids);
        }
        if(!empty($account_id)){
            $this->db->where('mc.worker_id', $account_id);
        }
        if(!empty($department_id)){
            $this->db->where('mc.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('mc_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mc_detail.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('mc_detail.tunch', $tunch);
        }
        if($type_sort == 'MCHAINIFW'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'MCHAINIS'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'MCHAINRFW'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'MCHAINRS'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_other_sell_item_for_stock_ledger($from_date, $to_date = '', $department_id = '', $category_id, $item_id, $tunch, $account_id, $type_sort){
        if($tunch == '0'){
            $this->db->select("oi.*, '0' AS less, grwt AS net_wt, '0' AS wstg, '0' AS fine, '0' AS touch_id, o.other_date as st_date, a.account_name, o.other_id as st_id, '". $type_sort ."' as type_sort");
            $this->db->from('other_items oi');
            $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
            $this->db->join('account a', 'a.account_id = o.account_id', 'left');
            if(!empty($to_date)){
                $this->db->where('o.other_date >=',$from_date);
                $this->db->where('o.other_date <=',$to_date);
            } else {
                $this->db->where('o.other_date <',$from_date);
            }
            $department_ids = $this->applib->current_user_department_ids();
            if(!empty($department_ids)){
                $this->db->where_in('o.department_id', $department_ids);
            }
            if(!empty($account_id)){
                $this->db->where('o.account_id', $account_id);
            }
            if(!empty($department_id)){
                $this->db->where('o.department_id', $department_id);
            }
            if(!empty($category_id)){
                $this->db->where('oi.category_id', $category_id);
            }
            if(!empty($item_id)){
                $this->db->where('oi.item_id', $item_id);
            }
    //        if(!empty($tunch)){
    //            $this->db->where('oi.touch_id', $tunch);
    //        }
            if($type_sort == 'O P'){
                $this->db->where('oi.type', OTHER_TYPE_PURCHASE_ID);
            }
            if($type_sort == 'O S'){
                $this->db->where('oi.type', OTHER_TYPE_SELL_ID);
            }
            $query = $this->db->get();
    //        echo '<pre>'.$this->db->last_query(); exit;
            return $query->result();
        } else {
            return array();
        }
    }
    
    function get_opening_stock_for_stock_ledger($department_id = '', $category_id = '', $item_id, $tunch, $account_id = '', $type_sort){
        $this->db->select("i.*, i.created_at as st_date, '". $type_sort ."' as type_sort, ntwt AS net_wt,tunch AS touch_id, 0 AS wstg,'' AS account_name");
        $this->db->from('opening_stock i');
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $this->db->where_in('i.department_id', $department_ids);
        }
        if(!empty($department_id)){
            $this->db->where('i.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('i.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('i.item_id', $item_id);
        }
        if(!empty($tunch)){
            $this->db->where('i.tunch', $tunch);
        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_sell_items_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
        if(PACKAGE_FOR == 'manek'){
            $this->db->select("si.*, IF(`si`.`type` = 1, si.grwt, '".ZERO_VALUE."'-si.grwt) AS grwt, IF(`si`.`type` = 1, si.less, '".ZERO_VALUE."'-si.less) AS less, IF(`si`.`type` = 1, si.net_wt, '".ZERO_VALUE."'-si.net_wt) AS net_wt, IF(`si`.`type` = 1, si.touch_id, '".ZERO_VALUE."'-si.touch_id) AS touch_id, IF(`si`.`type` = 1, si.wstg, '".ZERO_VALUE."'-si.wstg) AS wstg, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine , '".ZERO_VALUE."'-si.fine), '0'), '0') AS gold_fine, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0'), '0') AS silver_fine, IF(`si`.`type` = 1, si.amount, '".ZERO_VALUE."'-si.amount) AS amount, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        } else if($sell_purchase_type_3_menu == '1' ) {
            $this->db->select("si.*, IF(`si`.`type` = 1, si.grwt, '".ZERO_VALUE."'-si.grwt) AS grwt, IF(`si`.`type` = 1, si.less, '".ZERO_VALUE."'-si.less) AS less, IF(`si`.`type` = 1, si.net_wt, '".ZERO_VALUE."'-si.net_wt) AS net_wt, IF(`si`.`type` = 1, si.touch_id, '".ZERO_VALUE."'-si.touch_id) AS touch_id, IF(`si`.`type` = 1, si.wstg, '".ZERO_VALUE."'-si.wstg) AS wstg, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine , '".ZERO_VALUE."'-si.fine), '0')) AS gold_fine, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0')) AS silver_fine, IF(`si`.`type` = 1, si.charges_amt, '".ZERO_VALUE."'-si.charges_amt) AS amount, si.c_amt AS c_amt, si.r_amt As r_amt, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        } else {
            $this->db->select("si.*, IF(`si`.`type` = 1, si.grwt, '".ZERO_VALUE."'-si.grwt) AS grwt, IF(`si`.`type` = 1, si.less, '".ZERO_VALUE."'-si.less) AS less, IF(`si`.`type` = 1, si.net_wt, '".ZERO_VALUE."'-si.net_wt) AS net_wt, IF(`si`.`type` = 1, si.touch_id, '".ZERO_VALUE."'-si.touch_id) AS touch_id, IF(`si`.`type` = 1, si.wstg, '".ZERO_VALUE."'-si.wstg) AS wstg, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine , '".ZERO_VALUE."'-si.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0') AS silver_fine, si.charges_amt AS amount, si.c_amt AS c_amt, si.r_amt As r_amt, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'P'){
            $this->db->where('si.type', SELL_TYPE_PURCHASE_ID);
        }
        if($type_sort == 'S'){
            $this->db->where('si.type', SELL_TYPE_SELL_ID);
        }
        if($type_sort == 'E'){
            $this->db->where('si.type', SELL_TYPE_EXCHANGE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        return $query->result();
    }
    function get_sell_items_for_mfloss_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
        if($sell_purchase_type_3_menu == '1' ) {
            $this->db->select("si.*, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.charges_amt, si.charges_amt) AS amount, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        } else {
            $this->db->select("si.*, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."'- si.charges_amt AS amount, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        $this->db->where('si.charges_amt !=', '0');
        if($type_sort == 'P'){
            $this->db->where('si.type', SELL_TYPE_PURCHASE_ID);
        }
        if($type_sort == 'S'){
            $this->db->where('si.type', SELL_TYPE_SELL_ID);
        }
        if($type_sort == 'E'){
            $this->db->where('si.type', SELL_TYPE_EXCHANGE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        return $query->result();
    }

    function get_sell_discount_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $this->db->select("s.*, '' AS grwt, '' AS less, '' AS net_wt, '' AS touch_id, '' AS wstg, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - s.discount_amount AS amount, '".ZERO_VALUE."' - s.discount_amount AS c_amt, 0 As r_amt, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, a.account_name as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, '' AS group_name");
        $this->db->from('sell s');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        $this->db->where('s.discount_amount !=', '0');
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        return $query->result();
    }

    function get_sell_with_gst_amount_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $this->db->select("s.*, '' AS grwt, '' AS less, '' AS net_wt, '' AS touch_id, '' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.total_amount AS amount, s.total_amount AS c_amt, 0 As r_amt, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, a.account_name as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, '' AS group_name");
        $this->db->from('sell_with_gst s');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        $this->db->where('s.total_amount !=', '0');
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        return $query->result();
    }

    function get_metal_payment_receipt_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("mpr.*,mpr.metal_pr_id as id, mpr.metal_category_id as category_id, CONCAT(IF(mpr.metal_payment_receipt = 1,'','-'),mpr.metal_grwt) as grwt, CONCAT(IF(mpr.metal_payment_receipt = 1,'','-'),mpr.metal_tunch) as touch_id, '0' AS less, '0' AS net_wt, '0' AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(mpr.metal_payment_receipt = 1, '', '-'), mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(mpr.metal_payment_receipt = 1, '', '-'), mpr.metal_fine), '0') AS silver_fine, '0' AS amount, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, c.category_group_id AS group_name");
        $this->db->select("mpr.*, s.sell_no as reference_no, mpr.metal_category_id as category_id, IF(mpr.metal_payment_receipt = 1, mpr.metal_grwt, '".ZERO_VALUE."'-mpr.metal_grwt) as grwt, IF(mpr.metal_payment_receipt = 1, mpr.metal_tunch, '".ZERO_VALUE."'-mpr.metal_tunch) as touch_id, '0' AS less, IF(mpr.metal_payment_receipt = 1, mpr.metal_ntwt, '".ZERO_VALUE."'-mpr.metal_ntwt) AS net_wt, '0' AS wstg, IF(`c`.`category_group_id` = 1, IF(mpr.metal_payment_receipt = 1, mpr.metal_fine, '".ZERO_VALUE."'-mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(mpr.metal_payment_receipt = 1, mpr.metal_fine, '".ZERO_VALUE."'-mpr.metal_fine), '0') AS silver_fine, '0' AS amount, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, c.category_group_id AS group_name");
        $this->db->from('metal_payment_receipt mpr');
        $this->db->join('sell s', 's.sell_id = mpr.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = mpr.metal_category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mpr.metal_item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'M R'){
            $this->db->where('mpr.metal_payment_receipt', '2');
        }
        if($type_sort == 'M P'){
            $this->db->where('mpr.metal_payment_receipt', '1');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_payment_receipt_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, IF(pr.payment_receipt = 1, pr.c_amt, '".ZERO_VALUE."'-pr.c_amt) AS c_amt, IF(pr.payment_receipt = 1, pr.r_amt, '".ZERO_VALUE."'-pr.r_amt) AS r_amt, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'Payment'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'Receipt'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_payment_receipt_for_customer_ledger_bank($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' , CONCAT('Cheque @', a.account_name)) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('pr.bank_id', $account_id);
        }
        $this->db->where('pr.cash_cheque', '2');
        if($type_sort == 'Payment'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'Receipt'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_gold_bhav_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
//        $this->db->select("gb.*, gb.gold_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, CONCAT(IF(gb.gold_sale_purchase = 1, '-', ''), gb.gold_weight) AS gold_fine, '0' AS silver_fine, s.sell_date as st_date, CONCAT(IF(gb.gold_sale_purchase = 1, '', '-'), gb.gold_value) AS amount,  IF(gb.gold_sale_purchase = 1, 'Gold Sell' ,'Gold Purchase') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '1' AS group_name");
        $this->db->select("gb.*, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, IF(gb.gold_sale_purchase = 1, '".ZERO_VALUE."'-gb.gold_weight, gb.gold_weight) AS gold_fine, '0' AS silver_fine, s.sell_date as st_date, IF(gb.gold_sale_purchase = 1, gb.gold_value, '".ZERO_VALUE."'-gb.gold_value) AS amount, IF(gb.gold_sale_purchase = 1, gb.c_amt, '".ZERO_VALUE."'-gb.c_amt) AS c_amt, IF(gb.gold_sale_purchase = 1, gb.r_amt, '".ZERO_VALUE."'-gb.r_amt) AS r_amt,  IF(gb.gold_sale_purchase = 1, 'Gold Sell' ,'Gold Purchase') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '1' AS group_name");
        $this->db->from('gold_bhav gb');
        $this->db->join('sell s', 's.sell_id = gb.sell_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'GB S'){
            $this->db->where('gb.gold_sale_purchase', '1');
        }
        if($type_sort == 'GB P'){
            $this->db->where('gb.gold_sale_purchase', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_silver_bhav_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
//        $this->db->select("sb.*, sb.silver_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, CONCAT(IF(sb.silver_sale_purchase = 1, '-', ''), sb.silver_weight) AS silver_fine, '0' AS gold_fine, s.sell_date as st_date, CONCAT(IF(sb.silver_sale_purchase = 1, '', '-'), sb.silver_value) AS amount, IF(sb.silver_sale_purchase = 1, 'Silver Sell' ,'Silver Purchase') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '2' AS group_name");
        $this->db->select("sb.*, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, IF(sb.silver_sale_purchase = 1, '".ZERO_VALUE."'-sb.silver_weight, sb.silver_weight) AS silver_fine, '0' AS gold_fine, s.sell_date as st_date, IF(sb.silver_sale_purchase = 1, sb.silver_value, '".ZERO_VALUE."'-sb.silver_value) AS amount, IF(sb.silver_sale_purchase = 1, sb.c_amt, '".ZERO_VALUE."'-sb.c_amt) AS c_amt, IF(sb.silver_sale_purchase = 1, sb.r_amt, '".ZERO_VALUE."'-sb.r_amt) AS r_amt, IF(sb.silver_sale_purchase = 1, 'Silver Sell' ,'Silver Purchase') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '2' AS group_name");
        $this->db->from('silver_bhav sb');
        $this->db->join('sell s', 's.sell_id = sb.sell_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'SB S'){
            $this->db->where('sb.silver_sale_purchase', '1');
        }
        if($type_sort == 'SB P'){
            $this->db->where('sb.silver_sale_purchase', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_transfer_naam_jama_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("tr.*, tr.transfer_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_gold) AS gold_fine, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_silver) AS silver_fine, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_amount) AS amount, s.sell_date as st_date, a.account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("tr.*, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_gold, tr.transfer_gold) AS gold_fine, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_silver, tr.transfer_silver) AS silver_fine, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_amount, tr.transfer_amount) AS amount, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.c_amt, tr.c_amt) AS c_amt, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.r_amt, tr.r_amt) AS r_amt, s.sell_date as st_date, a.account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->from('transfer tr');
        $this->db->join('sell s', 's.sell_id = tr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = tr.transfer_account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'TR Naam'){
            $this->db->where('tr.naam_jama', '1');
        }
        if($type_sort == 'TR Jama'){
            $this->db->where('tr.naam_jama', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query1 = $this->db->get();
        $result1 = $query1->result();
        
//        $this->db->select("tr.*, tr.transfer_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_gold) AS gold_fine, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_silver) AS silver_fine, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_amount) AS amount, s.sell_date as st_date, a.account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("tr.*, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_gold, tr.transfer_gold) AS gold_fine, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_silver, tr.transfer_silver) AS silver_fine, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_amount, tr.transfer_amount) AS amount, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.c_amt, tr.c_amt) AS c_amt, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.r_amt, tr.r_amt) AS r_amt, s.sell_date as st_date, a.account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->from('transfer tr');
        $this->db->join('sell s', 's.sell_id = tr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('tr.transfer_account_id', $account_id);
        }
        if($type_sort == 'TR Naam'){
            $this->db->where('tr.naam_jama', '1');
        }
        if($type_sort == 'TR Jama'){
            $this->db->where('tr.naam_jama', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query2 = $this->db->get();
        $result2 = $query2->result();
        
//        echo "<pre>"; print_r($query->result()); exit;
        return array_merge($result1, $result2);
    }
    
    function get_sell_ad_charges_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->select("ad_c.*, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, ('".ZERO_VALUE."' - ad_c.ad_amount) AS amount, s.sell_date as st_date, CONCAT('Pcs: ', ad_c.ad_pcs, ' @Rate: ', ad_c.ad_rate) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        } else {
            $this->db->select("ad_c.*, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, ad_c.ad_amount AS amount, ad_c.c_amt AS c_amt, ad_c.r_amt AS r_amt, s.sell_date as st_date, CONCAT('Pcs: ', ad_c.ad_pcs, ' @Rate: ', ad_c.ad_rate) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        }
        $this->db->from('sell_ad_charges ad_c');
        $this->db->join('sell s', 's.sell_id = ad_c.sell_id', 'left');
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
        } else {
            $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        }
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            } else {
                $this->db->where('s.account_id', $account_id);
            }
        }
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_sell_adjust_cr_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
        $this->db->select("a_c.*, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, '0' AS amount, IF(a_c.adjust_to = 1, a_c.c_amt, '".ZERO_VALUE."'-a_c.c_amt) AS c_amt, IF(a_c.adjust_to = 1, '".ZERO_VALUE."'-a_c.r_amt, a_c.r_amt) AS r_amt, s.sell_date as st_date, CONCAT(' Adjust : ', IF(a_c.adjust_to = 1, 'R Amt to C Amt', 'C Amt to R Amt')) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->from('sell_adjust_cr a_c');
        $this->db->join('sell s', 's.sell_id = a_c.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_journal_naam_jama_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("jr.*, jr.jd_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(jr.type = 2, '-', ''), jr.amount) AS amount, j.journal_date as st_date, a.account_name, j.journal_id, '". $type_sort ."' as type_sort, j.department_id, j.interest_account_id, '4' AS group_name");
        $this->db->select("jr.*, j.journal_id as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, IF(jr.type = 2, '".ZERO_VALUE."'-jr.amount, jr.amount) AS amount, IF(jr.type = 2, '".ZERO_VALUE."'-jr.c_amt, jr.c_amt) AS c_amt, IF(jr.type = 2, '".ZERO_VALUE."'-jr.r_amt, jr.r_amt) AS r_amt, j.journal_date as st_date,
        	IF(j.is_module IN(1,2) OR j.interest_account_id IS NOT NULL,o_a.account_name,a.account_name) as account_name, 
        	j.journal_id, '". $type_sort ."' as type_sort, j.department_id, j.interest_account_id, '4' AS group_name");
        $this->db->from('journal_details jr');
        $this->db->join('journal j', 'j.journal_id = jr.journal_id', 'left');
        $this->db->join('account a', 'a.account_id = jr.account_id', 'left');
        $this->db->join('journal_details o_jr', '(j.is_module IN(1,2) OR j.interest_account_id IS NOT NULL) AND o_jr.journal_id = jr.journal_id AND o_jr.account_id != jr.account_id', 'left');
        $this->db->join('account o_a', 'o_a.account_id = o_jr.account_id', 'left');

//        if($type_sort == 'J Naam'){
//            $this->db->join('journal_details another_jr', 'another_jr.journal_id = jr.journal_id AND another_jr.account_id != jr.account_id AND another_jr.type = 2', 'left');
//            $this->db->join('account another_a', 'another_a.account_id = another_jr.account_id', 'left');
//        }
//        if($type_sort == 'J Jama'){
//            $this->db->join('journal_details another_jr', 'another_jr.journal_id = jr.journal_id AND another_jr.account_id != jr.account_id AND another_jr.type = 1', 'left');
//            $this->db->join('account another_a', 'another_a.account_id = another_jr.account_id', 'left');
//        }
        if(!empty($to_date)){
            $this->db->where('j.journal_date >=',$from_date);
            $this->db->where('j.journal_date <=',$to_date);
        } else {
            $this->db->where('j.journal_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('jr.account_id', $account_id);
        }
        if($type_sort == 'J Naam'){
            $this->db->where('jr.type', '1');
        }
        if($type_sort == 'J Jama'){
            $this->db->where('jr.type', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_cashbook_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, pr.pay_rec_id as pay_rece_id, pr.transaction_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, '". $type_sort ."' as type_sort, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, IF(pr.payment_receipt = 1, pr.c_amt, '".ZERO_VALUE."'-pr.c_amt) AS c_amt, IF(pr.payment_receipt = 1, pr.r_amt, '".ZERO_VALUE."'-pr.r_amt) AS r_amt, pr.pay_rec_id as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, pr.pay_rec_id as pay_rece_id, pr.transaction_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, '". $type_sort ."' as type_sort, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->where('pr.sell_id IS NULL', null, true);
        $this->db->where('pr.other_id IS NULL', null, true);
        if(!empty($to_date)){
            $this->db->where('pr.transaction_date >=',$from_date);
            $this->db->where('pr.transaction_date <=',$to_date);
        } else {
            $this->db->where('pr.transaction_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('pr.account_id', $account_id);
        }
        if($type_sort == 'C P'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'C R'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_issue_receive_karigar_wastage_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $this->db->select("ir.*, ir.ir_diffrence AS grwt, '' AS less, ir.ir_diffrence AS net_wt, '100' AS touch_id, 0 AS wstg, ir.ir_diffrence AS gold_fine, '0' AS silver_fine, '0' AS amount, ir.department_id AS department_id, ir.reference_no as reference_no, ir.ir_date as st_date, '' as account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, 0 as hisab_done, '1' AS group_name");
        $this->db->from('issue_receive ir');
        $this->db->join('account a', 'a.account_id = ir.worker_id', 'left');
        $this->db->where('ir.ir_diffrence !=', '0');
        if(!empty($to_date)){
            $this->db->where('ir.ir_date >=',$from_date);
            $this->db->where('ir.ir_date <=',$to_date);
        } else {
            $this->db->where('ir.ir_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('ir.worker_id', $account_id);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }

    function get_manufacture_issue_receive_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
//        $this->db->select("ird.*, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.weight) AS grwt, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.less) AS less, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.net_wt) AS net_wt, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS silver_fine, '0' AS amount, ir.department_id AS department_id, ird.ird_id as id, ir.ir_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, ir.hisab_done, c.category_group_id AS group_name");
        $this->db->select("ird.*, IF(`ird`.`type_id` = 1, ird.weight, '".ZERO_VALUE."'-ird.weight) AS grwt, IF(`ird`.`type_id` = 1, ird.less,'".ZERO_VALUE."'-ird.less) AS less, IF(`ird`.`type_id` = 1, ird.net_wt, '".ZERO_VALUE."'-ird.net_wt) AS net_wt, IF(`ird`.`type_id` = 1, ird.tunch,'".ZERO_VALUE."'-ird.tunch) AS touch_id, 0 AS wstg, IF(`ird`.`type_id` = 1, ird.fine, '".ZERO_VALUE."'-ird.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, ir.department_id AS department_id, ir.reference_no as reference_no, ird.ird_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, ir.hisab_done, c.category_group_id AS group_name");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->join('category c', 'c.category_id = ird.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ird.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('ird.ird_date >=',$from_date);
            $this->db->where('ird.ird_date <=',$to_date);
        } else {
            $this->db->where('ird.ird_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('ir.worker_id', $account_id);
        }
        if($type_sort == 'MFI'){
            $this->db->where('ird.type_id', MANUFACTURE_TYPE_ISSUE_ID);
        }
        if($type_sort == 'MFR'){
            $this->db->where('ird.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_silver_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
//        $this->db->select("irsd.*, CONCAT(IF(`irsd`.`type_id` = 1,'','-'),irsd.weight) AS grwt, CONCAT(IF(`irsd`.`type_id` = 1,'','-'),irsd.less) AS less, CONCAT(IF(`irsd`.`type_id` = 1,'','-'),irsd.net_wt) AS net_wt, CONCAT(IF(`irsd`.`type_id` = 1,'','-'),irsd.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(`irsd`.`type_id` = 1,'','-'),irsd.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`irsd`.`type_id` = 1,'','-'),irsd.fine), '0') AS silver_fine, '0' AS amount, irs.department_id AS department_id, irsd.irsd_id as id, irs.ir_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, irs.irs_id as st_id, '". $type_sort ."' as type_sort, irs.hisab_done, c.category_group_id AS group_name");
        $this->db->select("irsd.*, IF(`irsd`.`type_id` = 1, irsd.weight, '".ZERO_VALUE."'-irsd.weight) AS grwt, IF(`irsd`.`type_id` = 1, irsd.less,'".ZERO_VALUE."'-irsd.less) AS less, IF(`irsd`.`type_id` = 1, irsd.net_wt, '".ZERO_VALUE."'-irsd.net_wt) AS net_wt, IF(`irsd`.`type_id` = 1, irsd.tunch,'".ZERO_VALUE."'-irsd.tunch) AS touch_id, 0 AS wstg, '0' AS gold_fine, IF(`irsd`.`type_id` = 1, irsd.fine, '".ZERO_VALUE."'-irsd.fine) AS silver_fine, '0' AS amount, irs.department_id AS department_id, irs.reference_no as reference_no, irsd.irsd_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, irs.irs_id as st_id, '". $type_sort ."' as type_sort, irs.hisab_done, c.category_group_id AS group_name");
        $this->db->from('issue_receive_silver_details irsd');
        $this->db->join('issue_receive_silver irs', 'irs.irs_id = irsd.irs_id', 'left');
        $this->db->join('category c', 'c.category_id = irsd.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = irsd.item_id', 'left');
        $this->db->join('account a', 'a.account_id = irs.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('irsd.irsd_date >=',$from_date);
            $this->db->where('irsd.irsd_date <=',$to_date);
        } else {
            $this->db->where('irsd.irsd_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('irs.worker_id', $account_id);
        }
        if($type_sort == 'MFIS'){
            $this->db->where('irsd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
        }
        if($type_sort == 'MFRS'){
            $this->db->where('irsd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }
    
    function get_manufacture_manu_hand_made_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $this->db->select("mhm_detail.*, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', mhm_detail.weight, '".ZERO_VALUE."'-mhm_detail.weight) AS grwt, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', mhm_detail.less,'".ZERO_VALUE."'-mhm_detail.less) AS less, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', mhm_detail.net_wt, '".ZERO_VALUE."'-mhm_detail.net_wt) AS net_wt, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', mhm_detail.tunch,'".ZERO_VALUE."'-mhm_detail.tunch) AS touch_id, 0 AS wstg, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', mhm_detail.fine, '".ZERO_VALUE."'-mhm_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, mhm.department_id AS department_id, mhm.reference_no as reference_no, mhm_detail.mhm_detail_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, mhm.mhm_id as st_id, '". $type_sort ."' as type_sort, mhm.hisab_done, c.category_group_id AS group_name");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->join('category c', 'c.category_id = mhm_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mhm_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('mhm_detail.mhm_detail_date >=',$from_date);
            $this->db->where('mhm_detail.mhm_detail_date <=',$to_date);
        } else {
            $this->db->where('mhm_detail.mhm_detail_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('mhm.worker_id', $account_id);
        }
        if($type_sort == 'MHMIFW'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'MHMIS'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'MHMRFW'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'MHMRS'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_casting_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $this->db->select("ce_detail.*, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', ce_detail.weight, '".ZERO_VALUE."'-ce_detail.weight) AS grwt, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', ce_detail.less,'".ZERO_VALUE."'-ce_detail.less) AS less, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', ce_detail.net_wt, '".ZERO_VALUE."'-ce_detail.net_wt) AS net_wt, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', ce_detail.tunch,'".ZERO_VALUE."'-ce_detail.tunch) AS touch_id, 0 AS wstg, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', ce_detail.fine, '".ZERO_VALUE."'-ce_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, ce.department_id AS department_id, ce.reference_no as reference_no, ce_detail.ce_detail_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ce.ce_id as st_id, '". $type_sort ."' as type_sort, ce.hisab_done, c.category_group_id AS group_name");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->join('category c', 'c.category_id = ce_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ce_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ce.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('ce_detail.ce_detail_date >=',$from_date);
            $this->db->where('ce_detail.ce_detail_date <=',$to_date);
        } else {
            $this->db->where('ce_detail.ce_detail_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('ce.worker_id', $account_id);
        }
        if($type_sort == 'CASTINGIFW'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'CASTINGIS'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'CASTINGRFW'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'CASTINGRS'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_machin_chain_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        $this->db->select("mc_detail.*, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', mc_detail.weight, '".ZERO_VALUE."'-mc_detail.weight) AS grwt, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', mc_detail.less,'".ZERO_VALUE."'-mc_detail.less) AS less, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', mc_detail.net_wt, '".ZERO_VALUE."'-mc_detail.net_wt) AS net_wt, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', mc_detail.tunch,'".ZERO_VALUE."'-mc_detail.tunch) AS touch_id, 0 AS wstg, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', mc_detail.fine, '".ZERO_VALUE."'-mc_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, mc.department_id AS department_id, mc.reference_no as reference_no, mc_detail.machine_chain_detail_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, mc.machine_chain_id as st_id, '". $type_sort ."' as type_sort, mc.hisab_done, c.category_group_id AS group_name");
        $this->db->from('machine_chain_details mc_detail');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mc_detail.machine_chain_id', 'left');
        $this->db->join('category c', 'c.category_id = mc_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mc_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mc.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('mc_detail.machine_chain_detail_date >=',$from_date);
            $this->db->where('mc_detail.machine_chain_detail_date <=',$to_date);
        } else {
            $this->db->where('mc_detail.machine_chain_detail_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('mc.worker_id', $account_id);
        }
        if($type_sort == 'MCHAINIFW'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'MCHAINIS'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'MCHAINRFW'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'MCHAINRS'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_other_sell_item_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
//        $this->db->select("ird.*, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.weight) AS grwt, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.less) AS less, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.net_wt) AS net_wt, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS silver_fine, '0' AS amount, ir.department_id AS department_id, ird.ird_id as id, ir.ir_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, ir.hisab_done, c.category_group_id AS group_name");
        $this->db->select("oi.*, IF(`oi`.`type` = 1, oi.grwt, '".ZERO_VALUE."'-oi.grwt) AS grwt, '0' AS less, IF(`oi`.`type` = 1, oi.grwt, '".ZERO_VALUE."'-oi.grwt) AS net_wt, '0' AS touch_id, 0 AS wstg, '0' AS gold_fine, '0' AS silver_fine, IF(`oi`.`type` = 1, oi.amount, '".ZERO_VALUE."'-oi.amount) AS amount, o.department_id AS department_id, o.other_no as reference_no, o.other_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, o.other_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        $this->db->join('category c', 'c.category_id = oi.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = oi.item_id', 'left');
        $this->db->join('account a', 'a.account_id = o.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('o.other_date >=',$from_date);
            $this->db->where('o.other_date <=',$to_date);
        } else {
            $this->db->where('o.other_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('o.account_id', $account_id);
        }
        if($type_sort == 'O S'){
            $this->db->where('oi.type', OTHER_TYPE_SELL_ID);
        }
        if($type_sort == 'O P'){
            $this->db->where('oi.type', OTHER_TYPE_PURCHASE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_other_payment_receipt_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, o.other_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, o.other_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, o.other_id as st_id, '". $type_sort ."' as type_sort, o.department_id, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        if(!empty($to_date)){
            $this->db->where('o.other_date >=',$from_date);
            $this->db->where('o.other_date <=',$to_date);
        } else {
            $this->db->where('o.other_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('o.account_id', $account_id);
        }
        if($type_sort == 'O Payment'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'O Receipt'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_other_payment_receipt_for_customer_ledger_bank($from_date, $to_date = '', $account_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, o.other_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, o.other_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' , CONCAT('Cheque @', a.account_name)) as account_name, o.other_id as st_id, '". $type_sort ."' as type_sort, o.department_id, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('o.other_date >=',$from_date);
            $this->db->where('o.other_date <=',$to_date);
        } else {
            $this->db->where('o.other_date <',$from_date);
        }
        if(!empty($account_id)){
            $this->db->where('pr.bank_id', $account_id);
        }
        $this->db->where('pr.cash_cheque', '2');
        if($type_sort == 'O Payment'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'O Receipt'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_hisab_fine_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->select("wh.*, '".ZERO_VALUE."'-wh.fine AS grwt, '0' AS less, '".ZERO_VALUE."'-wh.fine AS net_wt, '100' AS touch_id, 0 AS wstg, '".ZERO_VALUE."'-wh.fine AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.department_id AS department_id, wh.worker_hisab_id as reference_no, wh.hisab_date as st_date, a_w.account_name as account_name, wh.worker_hisab_id as st_id, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        } else {
            $this->db->select("wh.*, wh.fine AS grwt, '0' AS less, wh.fine AS net_wt, '100' AS touch_id, 0 AS wstg, wh.fine AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.department_id AS department_id, wh.worker_hisab_id as reference_no, wh.hisab_date as st_date, a_mf.account_name as account_name, wh.worker_hisab_id as st_id, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        }
        $this->db->from('worker_hisab wh');
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        if(!empty($to_date)){
            $this->db->where('wh.hisab_date >=',$from_date);
            $this->db->where('wh.hisab_date <=',$to_date);
        } else {
            $this->db->where('wh.hisab_date <',$from_date);
        }
        if(!empty($account_id)){
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
                $this->db->where('wh.against_account_id', $account_id);
            } else {
                $this->db->where('wh.worker_id', $account_id);
            }
        }
        if($type_sort == 'Hisab Fine'){
            $this->db->where('wh.is_module', HISAB_DONE_IS_MODULE_MIR);
        }
        if($type_sort == 'MHM Hisab Fine'){
            $this->db->where('wh.is_module', HISAB_DONE_IS_MODULE_MHM);
        }
        if($type_sort == 'CASTING Hisab Fine'){
            $this->db->where('wh.is_module', HISAB_DONE_IS_MODULE_CASTING);
        }
        if($type_sort == 'MCHAIN Hisab Fine'){
            $this->db->where('wh.is_module', HISAB_DONE_IS_MODULE_MC);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_done_ir_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS grwt, '0' AS less, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS net_wt, '100' AS touch_id, 0 AS wstg, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.department_id AS department_id, whd.wsd_id as reference_no, wh.hisab_date as st_date, a_w.account_name as account_name, wh.worker_hisab_id as st_id, wh.created_at, wh.updated_at, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        } else {
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS grwt, '0' AS less, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS net_wt, '100' AS touch_id, 0 AS wstg, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.department_id AS department_id, whd.wsd_id as reference_no, wh.hisab_date as st_date, a_mf.account_name as account_name, wh.worker_hisab_id as st_id, wh.created_at, wh.updated_at, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        }
        $this->db->from('worker_hisab_detail whd');
        $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        if(!empty($to_date)){
            $this->db->where('wh.hisab_date >=',$from_date);
            $this->db->where('wh.hisab_date <=',$to_date);
        } else {
            $this->db->where('wh.hisab_date <',$from_date);
        }
        if(!empty($account_id)){
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
                $this->db->where('wh.against_account_id', $account_id);
            } else {
                $this->db->where('wh.worker_id', $account_id);
            }
        }
        if($type_sort == 'HD-I'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MIR);
        }
        if($type_sort == 'HD-R'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MIR);
        }
        if($type_sort == 'MHM HD-I'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MHM);
        }
        if($type_sort == 'MHM HD-R'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MHM);
        }
        if($type_sort == 'CASTING HD-I'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_CASTING);
        }
        if($type_sort == 'CASTING HD-R'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_CASTING);
        }
        if($type_sort == 'MCHAIN HD-I'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MC);
        }
        if($type_sort == 'MCHAIN HD-R'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MC);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_hisab_fine_silver_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->select("wh.*, '".ZERO_VALUE."'-wh.fine AS grwt, '0' AS less, '".ZERO_VALUE."'-wh.fine AS net_wt, '100' AS touch_id, 0 AS wstg, '0' AS gold_fine, '".ZERO_VALUE."'-wh.fine AS silver_fine, '0' AS amount, wh.department_id AS department_id, wh.worker_hisab_id as reference_no, wh.hisab_date as st_date, a_w.account_name as account_name, wh.worker_hisab_id as st_id, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        } else {
            $this->db->select("wh.*, wh.fine AS grwt, '0' AS less, wh.fine AS net_wt, '100' AS touch_id, 0 AS wstg, '0' AS gold_fine, wh.fine AS silver_fine, '0' AS amount, wh.department_id AS department_id, wh.worker_hisab_id as reference_no, wh.hisab_date as st_date, a_mf.account_name as account_name, wh.worker_hisab_id as st_id, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        }
        $this->db->from('worker_hisab wh');
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        if(!empty($to_date)){
            $this->db->where('wh.hisab_date >=',$from_date);
            $this->db->where('wh.hisab_date <=',$to_date);
        } else {
            $this->db->where('wh.hisab_date <',$from_date);
        }
        if(!empty($account_id)){
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
                $this->db->where('wh.against_account_id', $account_id);
            } else {
                $this->db->where('wh.worker_id', $account_id);
            }
        }
        if($type_sort == 'Hisab Fine S'){
            $this->db->where('wh.is_module', HISAB_DONE_IS_MODULE_MIR_SILVER);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_done_irs_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS grwt, '0' AS less, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS net_wt, '100' AS touch_id, 0 AS wstg, '0' AS gold_fine, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS silver_fine, '0' AS amount, wh.department_id AS department_id, whd.wsd_id as reference_no, wh.hisab_date as st_date, a_w.account_name as account_name, wh.worker_hisab_id as st_id, wh.created_at, wh.updated_at, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        } else {
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS grwt, '0' AS less, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS net_wt, '100' AS touch_id, 0 AS wstg, '0' AS gold_fine, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS silver_fine, '0' AS amount, wh.department_id AS department_id, whd.wsd_id as reference_no, wh.hisab_date as st_date, a_mf.account_name as account_name, wh.worker_hisab_id as st_id, wh.created_at, wh.updated_at, '". $type_sort ."' as type_sort, '".CATEGORY_GROUP_GOLD_ID."' AS group_name");
        }
        $this->db->from('worker_hisab_detail whd');
        $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
        if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        if(!empty($to_date)){
            $this->db->where('wh.hisab_date >=',$from_date);
            $this->db->where('wh.hisab_date <=',$to_date);
        } else {
            $this->db->where('wh.hisab_date <',$from_date);
        }
        if(!empty($account_id)){
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
                $this->db->where('wh.against_account_id', $account_id);
            } else {
                $this->db->where('wh.worker_id', $account_id);
            }
        }
        if($type_sort == 'HD-I S'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MIR_SILVER);
        }
        if($type_sort == 'HD-R S'){
            $this->db->where('whd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
            $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MIR_SILVER);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_hallmark_xrf_for_customer_ledger($from_date, $to_date = '', $account_id, $type_sort, $offset = 0){
        if($account_id == XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID){
            $this->db->select("xrf.*, '".ZERO_VALUE."' - xrf.total_amount AS amount, xrf.xrf_id as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, xrf.xrf_id as xrf_id, xrf.posting_date as st_date, '' as account_name, '". $type_sort ."' as type_sort, '4' AS group_name");
        } else {
            $this->db->select("xrf.*, xrf.total_amount AS amount, xrf.xrf_id as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, xrf.xrf_id as xrf_id, xrf.posting_date as st_date, '' as account_name, '". $type_sort ."' as type_sort, '4' AS group_name");
        }
        $this->db->from('hallmark_xrf xrf');
        if($account_id == XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID){
            $this->db->join('account a', 'a.account_id = '. XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, 'left');
        } else {
            $this->db->join('account a', 'a.account_id = xrf.account_id', 'left');
        }
        if(!empty($to_date)){
            $this->db->where('xrf.posting_date >=',$from_date);
            $this->db->where('xrf.posting_date <=',$to_date);
        } else {
            $this->db->where('xrf.posting_date <',$from_date);
        }
        if(!empty($account_id)){
            if($account_id == XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID){
            } else {
                $this->db->where('xrf.account_id', $account_id);
            }
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }

    function get_sell_items_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
        if(PACKAGE_FOR == 'manek'){
            $this->db->select("si.*, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.grwt, si.grwt) AS grwt, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.less, si.less) AS less, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.net_wt, si.net_wt) AS net_wt, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.touch_id, si.touch_id) AS touch_id, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.wstg, si.wstg) AS wstg, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0'), '0') AS gold_fine, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0'), '0') AS silver_fine, IF(`si`.`type` = 1, (si.amount - si.charges_amt), '".ZERO_VALUE."' - (si.amount - si.charges_amt)) AS amount, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        } else {
            $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
            if($sell_purchase_type_3_menu == '1' ) {
                $this->db->select("si.*, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.grwt, si.grwt) AS grwt, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.less, si.less) AS less, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.net_wt, si.net_wt) AS net_wt, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.touch_id, si.touch_id) AS touch_id, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.wstg, si.wstg) AS wstg, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0')) AS gold_fine, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0')) AS silver_fine, '0' AS amount, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
            } else {
                $this->db->select("si.*, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.grwt, si.grwt) AS grwt, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.less, si.less) AS less, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.net_wt, si.net_wt) AS net_wt, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.touch_id, si.touch_id) AS touch_id, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.wstg, si.wstg) AS wstg, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0') AS silver_fine, '0' AS amount, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
            }
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if($type_sort == 'P'){
            $this->db->where('si.type', SELL_TYPE_PURCHASE_ID);
        }
        if($type_sort == 'S'){
            $this->db->where('si.type', SELL_TYPE_SELL_ID);
        }
        if($type_sort == 'E'){
            $this->db->where('si.type', SELL_TYPE_EXCHANGE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_sell_with_gst_amount_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
        $this->db->select("s.*, '' AS grwt, '' AS less, '' AS net_wt, '' AS touch_id, '' AS wstg, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."'-s.total_amount AS amount, s.total_amount AS c_amt, 0 As r_amt, s.process_id AS department_id, s.sell_no as reference_no, s.sell_date as st_date, a.account_name as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, '' AS group_name");
        $this->db->from('sell_with_gst s');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        $this->db->where('s.total_amount !=', '0');
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        return $query->result();
    }

    function get_payment_receipt_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, s.sell_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        $this->db->where('pr.cash_cheque', '1');
        if($type_sort == 'Payment'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'Receipt'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_metal_payment_receipt_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset=0){
//        $this->db->select("mpr.*,mpr.metal_pr_id as id, mpr.metal_category_id as category_id, CONCAT(IF(mpr.metal_payment_receipt = 1,'-',''),mpr.metal_grwt) as grwt, CONCAT(IF(mpr.metal_payment_receipt = 1,'-',''),mpr.metal_tunch) as touch_id, '0' AS less, '0' AS net_wt, '0' AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(mpr.metal_payment_receipt = 1, '-', ''), mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(mpr.metal_payment_receipt = 1, '-', ''), mpr.metal_fine), '0') AS silver_fine, '0' AS amount, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, c.category_group_id AS group_name");
        $this->db->select("mpr.*, s.sell_no as reference_no, mpr.metal_category_id as category_id, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_grwt, mpr.metal_grwt) as grwt, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_tunch, mpr.metal_tunch) as touch_id, '0' AS less, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_ntwt, mpr.metal_ntwt) AS net_wt, '0' AS wstg, IF(`c`.`category_group_id` = 1, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_fine, mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_fine, mpr.metal_fine), '0') AS silver_fine, '0' AS amount, s.sell_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, c.category_group_id AS group_name");
        $this->db->from('metal_payment_receipt mpr');
        $this->db->join('sell s', 's.sell_id = mpr.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = mpr.metal_category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mpr.metal_item_id', 'left');
        if(!empty($to_date)){
            $this->db->where('s.sell_date >=',$from_date);
            $this->db->where('s.sell_date <=',$to_date);
        } else {
            $this->db->where('s.sell_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if($type_sort == 'M R'){
            $this->db->where('mpr.metal_payment_receipt', '2');
        }
        if($type_sort == 'M P'){
            $this->db->where('mpr.metal_payment_receipt', '1');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_cashbook_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, pr.pay_rec_id as pay_rece_id, pr.transaction_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, '". $type_sort ."' as type_sort, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, pr.pay_rec_id as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, pr.pay_rec_id as pay_rece_id, pr.transaction_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, '". $type_sort ."' as type_sort, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->where('pr.sell_id IS NULL', null, true);
        $this->db->where('pr.other_id IS NULL', null, true);
        if(!empty($to_date)){
            $this->db->where('pr.transaction_date >=',$from_date);
            $this->db->where('pr.transaction_date <=',$to_date);
        } else {
            $this->db->where('pr.transaction_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('pr.department_id', $department_id);
        }
        if($type_sort == 'C P'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'C R'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_stock_transfer_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
//        $this->db->select("std.*, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''),std.grwt) AS grwt, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''),std.less) AS less, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''),std.ntwt) AS net_wt, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''),std.tunch) AS touch_id, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''),std.wstg) AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''),std.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''),std.fine), '0') AS silver_fine, '0' AS amount, IF('".$type_sort."' = 'ST F',st.from_department, st.to_department) AS department_id, std.stock_transfer_id as id, st.transfer_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        $this->db->select("std.*, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.grwt, std.grwt) AS grwt, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.less, std.less) AS less, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.ntwt, std.ntwt) AS net_wt, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.tunch, std.tunch) AS touch_id, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.wstg, std.wstg) AS wstg, IF(`c`.`category_group_id` = 1, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.fine, std.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.fine, std.fine), '0') AS silver_fine, '0' AS amount, IF('".$type_sort."' = 'ST F',st.from_department, st.to_department) AS department_id, std.stock_transfer_id as stock_transfer_id, std.stock_transfer_id as reference_no, st.transfer_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        $this->db->from('stock_transfer_detail std');
        $this->db->join('stock_transfer st', 'st.stock_transfer_id = std.stock_transfer_id', 'left');
        $this->db->join('category c', 'c.category_id = std.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = std.item_id', 'left');
        if(!empty($to_date)){
            $this->db->where('st.transfer_date >=',$from_date);
            $this->db->where('st.transfer_date <=',$to_date);
        } else {
            $this->db->where('st.transfer_date <',$from_date);
        }
        if($type_sort == 'ST F' && !empty($department_id)){
            $this->db->where('st.from_department', $department_id);
        }
        if($type_sort == 'ST T' && !empty($department_id)){
            $this->db->where('st.to_department', $department_id);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo $this->db->last_query();
        return $query->result();
    }
    
    function get_manufacture_issue_receive_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
//        $this->db->select("ird.*, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.weight) AS grwt, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.less) AS less, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.net_wt) AS net_wt, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS silver_fine, '0' AS amount, ir.department_id AS department_id, ird.ird_id as id, ir.ir_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, ir.hisab_done, c.category_group_id AS group_name");
        $this->db->select("ird.*, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.weight, ird.weight) AS grwt, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.less, ird.less) AS less, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.net_wt, ird.net_wt) AS net_wt, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.tunch, ird.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.fine, ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.fine, ird.fine), '0') AS silver_fine, '0' AS amount, ir.department_id AS department_id, ir.reference_no as reference_no, ird.ird_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, ir.hisab_done, c.category_group_id AS group_name");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->join('category c', 'c.category_id = ird.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ird.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('ird.ird_date >=',$from_date);
            $this->db->where('ird.ird_date <=',$to_date);
        } else {
            $this->db->where('ird.ird_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('ir.department_id', $department_id);
        }
        if($type_sort == 'MFI'){
            $this->db->where('ird.type_id', MANUFACTURE_TYPE_ISSUE_ID);
        }
        if($type_sort == 'MFR'){
            $this->db->where('ird.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_issue_receive_silver_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
//        $this->db->select("irsd.*, CONCAT(IF(`irsd`.`type_id` = 1,'-',''),irsd.weight) AS grwt, CONCAT(IF(`irsd`.`type_id` = 1,'-',''),irsd.less) AS less, CONCAT(IF(`irsd`.`type_id` = 1,'-',''),irsd.net_wt) AS net_wt, CONCAT(IF(`irsd`.`type_id` = 1,'-',''),irsd.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(`irsd`.`type_id` = 1,'-',''),irsd.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`irsd`.`type_id` = 1,'-',''),irsd.fine), '0') AS silver_fine, '0' AS amount, irs.department_id AS department_id, irsd.irsd_id as id, irs.irs_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, irs.irs_id as st_id, '". $type_sort ."' as type_sort, irs.hisab_done, c.category_group_id AS group_name");
        $this->db->select("irsd.*, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.weight, irsd.weight) AS grwt, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.less, irsd.less) AS less, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.net_wt, irsd.net_wt) AS net_wt, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.tunch, irsd.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.fine, irsd.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.fine, irsd.fine), '0') AS silver_fine, '0' AS amount, irs.department_id AS department_id, irs.reference_no as reference_no, irsd.irsd_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, irs.irs_id as st_id, '". $type_sort ."' as type_sort, irs.hisab_done, c.category_group_id AS group_name");
        $this->db->from('issue_receive_silver_details irsd');
        $this->db->join('issue_receive_silver irs', 'irs.irs_id = irsd.irs_id', 'left');
        $this->db->join('category c', 'c.category_id = irsd.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = irsd.item_id', 'left');
        $this->db->join('account a', 'a.account_id = irs.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('irsd.irsd_date >=',$from_date);
            $this->db->where('irsd.irsd_date <=',$to_date);
        } else {
            $this->db->where('irsd.irsd_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('irs.department_id', $department_id);
        }
        if($type_sort == 'MFIS'){
            $this->db->where('irsd.type_id', MANUFACTURE_TYPE_ISSUE_ID);
        }
        if($type_sort == 'MFRS'){
            $this->db->where('irsd.type_id', MANUFACTURE_TYPE_RECEIVE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_manu_hand_made_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
        $this->db->select("mhm_detail.*, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.weight, mhm_detail.weight) AS grwt, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.less, mhm_detail.less) AS less, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.net_wt, mhm_detail.net_wt) AS net_wt, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.tunch, mhm_detail.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.fine, mhm_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.fine, mhm_detail.fine), '0') AS silver_fine, '0' AS amount, mhm.department_id AS department_id, mhm.reference_no as reference_no, mhm_detail.mhm_detail_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, mhm.mhm_id as st_id, '". $type_sort ."' as type_sort, mhm.hisab_done, c.category_group_id AS group_name");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->join('category c', 'c.category_id = mhm_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mhm_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('mhm_detail.mhm_detail_date >=',$from_date);
            $this->db->where('mhm_detail.mhm_detail_date <=',$to_date);
        } else {
            $this->db->where('mhm_detail.mhm_detail_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('mhm.department_id', $department_id);
        }
        if($type_sort == 'MHMIFW'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'MHMIS'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'MHMRFW'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'MHMRS'){
            $this->db->where('mhm_detail.type_id', MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_casting_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
        $this->db->select("ce_detail.*, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.weight, ce_detail.weight) AS grwt, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.less, ce_detail.less) AS less, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.net_wt, ce_detail.net_wt) AS net_wt, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.tunch, ce_detail.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.fine, ce_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.fine, ce_detail.fine), '0') AS silver_fine, '0' AS amount, ce.department_id AS department_id, ce.reference_no as reference_no, ce_detail.ce_detail_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ce.ce_id as st_id, '". $type_sort ."' as type_sort, ce.hisab_done, c.category_group_id AS group_name");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->join('category c', 'c.category_id = ce_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ce_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ce.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('ce_detail.ce_detail_date >=',$from_date);
            $this->db->where('ce_detail.ce_detail_date <=',$to_date);
        } else {
            $this->db->where('ce_detail.ce_detail_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('ce.department_id', $department_id);
        }
        if($type_sort == 'CASTINGIFW'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'CASTINGIS'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'CASTINGRFW'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'CASTINGRS'){
            $this->db->where('ce_detail.type_id', CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_manufacture_machin_chain_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
        $this->db->select("mc_detail.*, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.weight, mc_detail.weight) AS grwt, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.less, mc_detail.less) AS less, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.net_wt, mc_detail.net_wt) AS net_wt, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.tunch, mc_detail.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.fine, mc_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.fine, mc_detail.fine), '0') AS silver_fine, '0' AS amount, mc.department_id AS department_id, mc.reference_no as reference_no, mc_detail.machine_chain_detail_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, mc.machine_chain_id as st_id, '". $type_sort ."' as type_sort, mc.hisab_done, c.category_group_id AS group_name");
        $this->db->from('machine_chain_details mc_detail');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mc_detail.machine_chain_id', 'left');
        $this->db->join('category c', 'c.category_id = mc_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mc_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mc.worker_id', 'left');
        if(!empty($to_date)){
            $this->db->where('mc_detail.machine_chain_detail_date >=',$from_date);
            $this->db->where('mc_detail.machine_chain_detail_date <=',$to_date);
        } else {
            $this->db->where('mc_detail.machine_chain_detail_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('mc.department_id', $department_id);
        }
        if($type_sort == 'MCHAINIFW'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID);
        }
        if($type_sort == 'MCHAINIS'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID);
        }
        if($type_sort == 'MCHAINRFW'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID);
        }
        if($type_sort == 'MCHAINRS'){
            $this->db->where('mc_detail.type_id', MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_other_sell_item_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset = 0){
//        $this->db->select("ird.*, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.weight) AS grwt, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.less) AS less, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.net_wt) AS net_wt, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.tunch) AS touch_id, 0 AS wstg, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS silver_fine, '0' AS amount, ir.department_id AS department_id, ird.ird_id as id, ir.ir_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, ir.ir_id as st_id, '". $type_sort ."' as type_sort, ir.hisab_done, c.category_group_id AS group_name");
        $this->db->select("oi.*, IF(`oi`.`type` = 1, '".ZERO_VALUE."'-oi.grwt, oi.grwt) AS grwt, '0' AS less, IF(`oi`.`type` = 1, '".ZERO_VALUE."'-oi.grwt, oi.grwt) AS net_wt, '0' AS touch_id, 0 AS wstg, '0' AS gold_fine, '0' AS silver_fine, IF(`oi`.`type` = 1, '".ZERO_VALUE."'-oi.amount, oi.amount) AS amount, o.department_id AS department_id, o.other_no as reference_no, o.other_date as st_date, CONCAT(c.category_name, ' - ', item.item_name) as account_name, o.other_id as st_id, '". $type_sort ."' as type_sort, c.category_group_id AS group_name");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        $this->db->join('category c', 'c.category_id = oi.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = oi.item_id', 'left');
        $this->db->join('account a', 'a.account_id = o.account_id', 'left');
        if(!empty($to_date)){
            $this->db->where('o.other_date >=',$from_date);
            $this->db->where('o.other_date <=',$to_date);
        } else {
            $this->db->where('o.other_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('o.department_id', $department_id);
        }
        if($type_sort == 'O S'){
            $this->db->where('oi.type', OTHER_TYPE_SELL_ID);
        }
        if($type_sort == 'O P'){
            $this->db->where('oi.type', OTHER_TYPE_PURCHASE_ID);
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_other_payment_receipt_for_customer_ledger_department($from_date, $to_date = '', $department_id, $type_sort, $offset=0){
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, pr.pay_rec_id as id, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, s.sell_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, s.sell_id as st_id, '". $type_sort ."' as type_sort, s.process_id AS department_id, '4' AS group_name");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, o.other_no as reference_no, '0' AS grwt, '0' AS less, '0' AS net_wt, '0' AS touch_id, '0' AS wstg, '0' AS gold_fine, '0' AS silver_fine, o.other_date as st_date,  IF(pr.cash_cheque = 1, 'Cash' ,'Cheque') as account_name, o.other_id as st_id, '". $type_sort ."' as type_sort, o.department_id, '4' AS group_name");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        if(!empty($to_date)){
            $this->db->where('o.other_date >=',$from_date);
            $this->db->where('o.other_date <=',$to_date);
        } else {
            $this->db->where('o.other_date <',$from_date);
        }
        if(!empty($department_id)){
            $this->db->where('o.department_id', $department_id);
        }
        $this->db->where('pr.cash_cheque', '1');
        if($type_sort == 'O Payment'){
            $this->db->where('pr.payment_receipt', '1');
        }
        if($type_sort == 'O Receipt'){
            $this->db->where('pr.payment_receipt', '2');
        }
//        $this->db->limit(5);
        $this->db->offset($offset);
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }
    
    function get_sell_item_ids($sell_id){
        $sell_item_ids = array();
        $sell_item_arr = $this->crud->getFromSQL('SELECT `sell_item_id` FROM `sell_items` WHERE `sell_id` = "' . $sell_id . '"');
        foreach ($sell_item_arr as $sell_item){
            $sell_item_ids[] = $sell_item->sell_item_id;
        }
        return $sell_item_ids;
    }
    
    function get_purchase_sell_item_ids($sell_id){
        $sell_item_ids = array();
        $sell_item_arr = $this->crud->getFromSQL('SELECT `purchase_sell_item_id` FROM `sell_items` WHERE `sell_id` = "' . $sell_id . '"');
        foreach ($sell_item_arr as $sell_item){
            $sell_item_ids[] = $sell_item->purchase_sell_item_id;
        }
        return $sell_item_ids;
    }
    
    function get_stock_transfer_detail_ids($stock_transfer_id){
        $stock_transfer_detail_ids = array();
        $stock_transfer_detail_arr = $this->crud->getFromSQL('SELECT `transfer_detail_id` FROM `stock_transfer_detail` WHERE `stock_transfer_id` = "' . $stock_transfer_id . '"');
        foreach ($stock_transfer_detail_arr as $stock_transfer_detail){
            $stock_transfer_detail_ids[] = $stock_transfer_detail->transfer_detail_id;
        }
        return $stock_transfer_detail_ids;
    }
    
    function get_stock_transfer_detail_purchase_sell_item_ids($stock_transfer_id){
        $stock_transfer_detail_ids = array();
        $stock_transfer_detail_arr = $this->crud->getFromSQL('SELECT `purchase_sell_item_id` FROM `stock_transfer_detail` WHERE `stock_transfer_id` = "' . $stock_transfer_id . '"');
        foreach ($stock_transfer_detail_arr as $stock_transfer_detail){
            $stock_transfer_detail_ids[] = $stock_transfer_detail->purchase_sell_item_id;
        }
        return $stock_transfer_detail_ids;
    }
    
    function get_issue_receive_item_ids($ir_id){
        $manufacture_item_ids = array();
        $manufacture_item_arr = $this->crud->getFromSQL('SELECT `ird_id` FROM `issue_receive_details` WHERE `ir_id` = "' . $ir_id . '"');
        foreach ($manufacture_item_arr as $manufacture_item){
            $manufacture_item_ids[] = $manufacture_item->ird_id;
        }
        return $manufacture_item_ids;
    }
    
    function get_issue_receive_purchase_sell_item_ids($ir_id){
        $manufacture_item_ids = array();
        $manufacture_item_arr = $this->crud->getFromSQL('SELECT `purchase_sell_item_id` FROM `issue_receive_details` WHERE `ir_id` = "' . $ir_id . '"');
        foreach ($manufacture_item_arr as $manufacture_item){
            $manufacture_item_ids[] = $manufacture_item->purchase_sell_item_id;
        }
        return $manufacture_item_ids;
    }
    function get_manu_hand_made_item_ids($mhm_id){
        $manu_hand_made_item_ids = array();
        $manu_hand_made_item_arr = $this->crud->getFromSQL('SELECT `mhm_detail_id` FROM `manu_hand_made_details` WHERE `mhm_id` = "' . $mhm_id . '"');
        foreach ($manu_hand_made_item_arr as $manu_hand_made_item){
            $manu_hand_made_item_ids[] = $manu_hand_made_item->mhm_detail_id;
        }
        return $manu_hand_made_item_ids;
    }
    
    function get_manu_hand_made_purchase_sell_item_ids($mhm_id){
        $manu_hand_made_item_ids = array();
        $manu_hand_made_item_arr = $this->crud->getFromSQL('SELECT `purchase_sell_item_id` FROM `manu_hand_made_details` WHERE `mhm_id` = "' . $mhm_id . '"');
        foreach ($manu_hand_made_item_arr as $manu_hand_made_item){
            $manu_hand_made_item_ids[] = $manu_hand_made_item->purchase_sell_item_id;
        }
        return $manu_hand_made_item_ids;
    }
    function get_machine_chain_item_ids($machine_chain_id){
        $machine_chain_item_ids = array();
        $machine_chain_item_arr = $this->crud->getFromSQL('SELECT `machine_chain_detail_id` FROM `machine_chain_details` WHERE `machine_chain_id` = "' . $machine_chain_id . '"');
        foreach ($machine_chain_item_arr as $machine_chain_item){
            $machine_chain_item_ids[] = $machine_chain_item->machine_chain_detail_id;
        }
        return $machine_chain_item_ids;
    }
    
    function get_machine_chain_purchase_sell_item_ids($machine_chain_id){
        $machine_chain_item_ids = array();
        $machine_chain_item_arr = $this->crud->getFromSQL('SELECT `purchase_sell_item_id` FROM `machine_chain_details` WHERE `machine_chain_id` = "' . $machine_chain_id . '"');
        foreach ($machine_chain_item_arr as $machine_chain_item){
            $machine_chain_item_ids[] = $machine_chain_item->purchase_sell_item_id;
        }
        return $machine_chain_item_ids;
    }

    /*********  Outstanding Report Related Function Start ***********/
    function get_sell_items_for_outstanding_report($upto_balance_date, $account_group_id) {
        if(PACKAGE_FOR == 'manek'){
            $this->db->select("si.*, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0'), '0') AS gold_fine, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0'), '0') AS silver_fine, IF(`si`.`type` = 1, si.amount, '".ZERO_VALUE."'-si.amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
            if($sell_purchase_type_3_menu == '1' ) {
                $this->db->select("si.*, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0')) AS gold_fine, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0')) AS silver_fine, IF(`si`.`type` = 1, si.charges_amt, '".ZERO_VALUE."'-si.charges_amt) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            } else {
                $this->db->select("si.*, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0') AS silver_fine, si.charges_amt AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            }
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    function get_sell_items_for_mfloss_outstanding_report($upto_balance_date, $account_group_id) {
        $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
        if($sell_purchase_type_3_menu == '1' ) {
            $this->db->select("si.*, '0' AS gold_fine, '0' AS silver_fine, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.charges_amt, si.charges_amt) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("si.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."'-si.charges_amt AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        $this->db->join('account a', 'a.account_id = '.MF_LOSS_EXPENSE_ACCOUNT_ID, 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        $this->db->where('si.charges_amt !=', '0');
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    function get_sell_discount_for_outstanding_report($upto_balance_date, $account_group_id) {
        $this->db->select("s.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - s.discount_amount AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('sell s');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    function get_sell_with_gst_amount_for_outstanding_report($upto_balance_date, $account_group_id) {
        $this->db->select("s.*, '0' AS gold_fine, '0' AS silver_fine, s.total_amount AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('sell_with_gst s');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }

    function get_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_payment_receipt_for_outstanding_report_bank($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.bank_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '2');
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_metal_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id) {
//        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(mpr.metal_payment_receipt = 1, '', '-'), mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(mpr.metal_payment_receipt = 1, '', '-'), mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, IF(mpr.metal_payment_receipt = 1, mpr.metal_fine, '".ZERO_VALUE."'-mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(mpr.metal_payment_receipt = 1, mpr.metal_fine, '".ZERO_VALUE."'-mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('metal_payment_receipt mpr');
        $this->db->join('sell s', 's.sell_id = mpr.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = mpr.metal_category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mpr.metal_item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        //print_r($this->db->last_query());die;
        return $query->result();
    }

    function get_gold_bhav_for_outstanding_report($upto_balance_date, $account_group_id) {
//        $this->db->select("gb.*,  CONCAT(IF(gb.gold_sale_purchase = 1, '-', ''), gb.gold_weight) AS gold_fine, CONCAT(IF(gb.gold_sale_purchase = 1, '', '-'), gb.gold_value) AS amount, '0' AS silver_fine, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("gb.*,  IF(gb.gold_sale_purchase = 1, '".ZERO_VALUE."'-gb.gold_weight, gb.gold_weight) AS gold_fine, IF(gb.gold_sale_purchase = 1, gb.gold_value, '".ZERO_VALUE."'-gb.gold_value) AS amount, '0' AS silver_fine, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('gold_bhav gb');
        $this->db->join('sell s', 's.sell_id = gb.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_silver_bhav_for_outstanding_report($upto_balance_date, $account_group_id) {
//        $this->db->select("sb.*, CONCAT(IF(sb.silver_sale_purchase = 1, '-', ''), sb.silver_weight) AS silver_fine, '0' AS gold_fine, CONCAT(IF(sb.silver_sale_purchase = 1, '', '-'), sb.silver_value) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("sb.*, IF(sb.silver_sale_purchase = 1, '".ZERO_VALUE."'-sb.silver_weight, sb.silver_weight) AS silver_fine, '0' AS gold_fine, IF(sb.silver_sale_purchase = 1, sb.silver_value, '".ZERO_VALUE."'-sb.silver_value) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('silver_bhav sb');
        $this->db->join('sell s', 's.sell_id = sb.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }

    function get_transfer_naam_jama_for_outstanding_report($upto_balance_date, $account_id, $type_sort, $account_group_id) {
//        $this->db->select("tr.*, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_gold) AS gold_fine, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_silver) AS silver_fine, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->select("tr.*, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_gold, tr.transfer_gold) AS gold_fine, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_silver, tr.transfer_silver) AS silver_fine, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_amount, tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->from('transfer tr');
        $this->db->join('sell s', 's.sell_id = tr.sell_id', 'left');
//        $this->db->join('account a', 'a.account_id = tr.transfer_account_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
//        if(!empty($account_group_id)){
//            $this->db->where('a.account_group_id', $account_group_id);
//        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'TR Naam'){
            $this->db->where('tr.naam_jama', '1');
        }
        if($type_sort == 'TR Jama'){
            $this->db->where('tr.naam_jama', '2');
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query1 = $this->db->get();
        $result1 = $query1->result();
        
//        $this->db->select("tr.*, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_gold) AS gold_fine, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_silver) AS silver_fine, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->select("tr.*, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_gold, tr.transfer_gold) AS gold_fine, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_silver, tr.transfer_silver) AS silver_fine, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_amount, tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->from('transfer tr');
        $this->db->join('sell s', 's.sell_id = tr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = tr.transfer_account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_id)){
            $this->db->where('tr.transfer_account_id', $account_id);
        }
        if($type_sort == 'TR Naam'){
            $this->db->where('tr.naam_jama', '1');
        }
        if($type_sort == 'TR Jama'){
            $this->db->where('tr.naam_jama', '2');
        }
//        if(!empty($account_group_id)){
//            $this->db->where('a.account_group_id', $account_group_id);
//        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query2 = $this->db->get();
        $result2 = $query2->result();
        
//        echo "<pre>"; print_r($query->result()); exit;
        return array_merge($result1, $result2);
    }

    function get_journal_naam_jama_for_outstanding_report($upto_balance_date, $account_group_id) {
//        $this->db->select("jr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(jr.type = 2, '-', ''), jr.amount) AS amount, j.journal_date as st_date, a.account_id, a.account_name, a.account_mobile, j.journal_id, a.credit_limit, a.account_group_id");
        $this->db->select("jr.*, '0' AS gold_fine, '0' AS silver_fine, IF(jr.type = 2, '".ZERO_VALUE."'-jr.amount, jr.amount) AS amount, j.journal_date as st_date, a.account_id, a.account_name, a.account_mobile, j.journal_id, a.credit_limit, a.account_group_id");
        $this->db->from('journal_details jr');
        $this->db->join('journal j', 'j.journal_id = jr.journal_id', 'LEFT');
        $this->db->join('account a', 'a.account_id = jr.account_id', 'LEFT');
        $this->db->where('j.journal_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
 //       echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }

    function get_cashbook_for_outstanding_report($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        //$this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.account_id', 'left');
        $this->db->where('pr.sell_id IS NULL', null, true);
        $this->db->where('pr.other_id IS NULL', null, true);
        $this->db->where('pr.transaction_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
 //       echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_for_outstanding_report($upto_balance_date, $account_group_id){
//        $this->db->select("ird.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("ird.*, IF(`ird`.`type_id` = 1, ird.fine, '".ZERO_VALUE."'-ird.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->join('category c', 'c.category_id = ird.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ird.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.worker_id', 'left');
        $this->db->where('ird.ird_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_silver_for_outstanding_report($upto_balance_date, $account_group_id){
        $this->db->select("irsd.*, '0' AS gold_fine, IF(`irsd`.`type_id` = 1, irsd.fine, '".ZERO_VALUE."'-irsd.fine) AS silver_fine, '0' AS amount, irsd.irsd_date as st_date,a.account_id, a.account_name, a.account_mobile, irsd.irsd_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_silver_details irsd');
        $this->db->join('issue_receive_silver irs', 'irs.irs_id = irsd.irs_id', 'left');
        $this->db->join('category c', 'c.category_id = irsd.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = irsd.item_id', 'left');
        $this->db->join('account a', 'a.account_id = irs.worker_id', 'left');
        $this->db->where('irsd.irsd_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_manu_hand_made_for_outstanding_report($upto_balance_date, $account_group_id){
        $this->db->select("mhm_detail.*, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', mhm_detail.fine, '".ZERO_VALUE."'-mhm_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, mhm_detail.mhm_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mhm_detail.mhm_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->join('category c', 'c.category_id = mhm_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mhm_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.worker_id', 'left');
        $this->db->where('mhm_detail.mhm_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_casting_for_outstanding_report($upto_balance_date, $account_group_id){
        $this->db->select("ce_detail.*, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', ce_detail.fine, '".ZERO_VALUE."'-ce_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, ce_detail.ce_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, ce_detail.ce_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->join('category c', 'c.category_id = ce_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ce_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ce.worker_id', 'left');
        $this->db->where('ce_detail.ce_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_machin_chain_for_outstanding_report($upto_balance_date, $account_group_id){
        $this->db->select("mc_detail.*, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', mc_detail.fine, '".ZERO_VALUE."'-mc_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, mc_detail.machine_chain_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mc_detail.machine_chain_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('machine_chain_details mc_detail');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mc_detail.machine_chain_id', 'left');
        $this->db->join('category c', 'c.category_id = mc_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mc_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mc.worker_id', 'left');
        $this->db->where('mc_detail.machine_chain_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_sell_items_for_outstanding_report($upto_balance_date, $account_group_id){
        $this->db->select("oi.*, '0' AS gold_fine, '0' AS silver_fine, IF(`oi`.`type` = 1, oi.amount, '".ZERO_VALUE."'-oi.amount) AS amount, o.department_id AS department_id, oi.other_item_id as id, o.other_date as st_date, o.other_id as st_id,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        $this->db->join('category c', 'c.category_id = oi.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = oi.item_id', 'left');
        $this->db->join('account a', 'a.account_id = o.account_id', 'left');
        $this->db->where('o.other_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, o.other_date as st_date, o.other_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        $this->db->join('account a', 'a.account_id = o.account_id', 'left');
        $this->db->where('o.other_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_other_payment_receipt_for_outstanding_report_bank($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, o.other_date as st_date, o.other_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.bank_id', 'left');
        $this->db->where('o.other_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '2');
        $this->db->group_start();
            $this->db->or_where('a.gold_fine !=', '0');
            $this->db->or_where('a.silver_fine !=', '0');
            $this->db->or_where('a.amount !=', '0');
        $this->db->group_end();
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_account_ids_from_account_group_id($account_group_id){
        $account_ids = array();
        $account_arr = $this->getFromSQL('SELECT `account_id` FROM `account` WHERE `account_group_id` = "' . $account_group_id . '"');
        foreach ($account_arr as $account){
            $account_ids[] = $account->account_id;
        }
        return $account_ids;
    }
    
    function get_sell_ad_charges_for_outstanding_report($upto_balance_date, $account_group_id, $for_account = ''){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("ad_c.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - ad_c.ad_amount AS amount, s.sell_date as st_date, a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else if($for_account == 'mfloss'){
            $this->db->select("ad_c.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - ad_c.ad_amount AS amount, s.sell_date as st_date, a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("ad_c.*, '0' AS gold_fine, '0' AS silver_fine, ad_c.ad_amount AS amount, s.sell_date as st_date, a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('sell_ad_charges ad_c');
        $this->db->join('sell s', 's.sell_id = ad_c.sell_id', 'left');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = '.MF_LOSS_EXPENSE_ACCOUNT_ID, 'left');
        } else if($for_account == 'mfloss'){
            $this->db->join('account a', 'a.account_id = '.MF_LOSS_EXPENSE_ACCOUNT_ID, 'left');
        } else {
            $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        }
        $this->db->where('s.sell_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_fine_for_outstanding_report($upto_balance_date, $account_group_id, $for_account = ''){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("wh.*, '".ZERO_VALUE."'-wh.fine AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        } else if($for_account == 'mfloss'){
            $this->db->select("wh.*, '".ZERO_VALUE."'-wh.fine AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("wh.*, wh.fine AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab wh');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else if($for_account == 'mfloss'){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module !=',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_done_ir_for_outstanding_report($upto_balance_date, $account_group_id, $for_account = ''){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        } else if($for_account == 'mfloss'){
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab_detail whd');
        $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else if($for_account == 'mfloss'){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module !=',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_hisab_fine_silver_for_outstanding_report($upto_balance_date, $account_group_id, $for_account = ''){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("wh.*, '0' AS gold_fine, '".ZERO_VALUE."'-wh.fine AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        } else if($for_account == 'mfloss'){
            $this->db->select("wh.*, '0' AS gold_fine, '".ZERO_VALUE."'-wh.fine AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("wh.*, '0' AS gold_fine, wh.fine AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab wh');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else if($for_account == 'mfloss'){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_done_irs_for_outstanding_report($upto_balance_date, $account_group_id, $for_account = ''){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("whd.*, '0' AS gold_fine, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        } else if($for_account == 'mfloss'){
            $this->db->select("whd.*, '0' AS gold_fine, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("whd.*, '0' AS gold_fine, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab_detail whd');
        $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else if($for_account == 'mfloss'){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_hallmark_xrf_for_outstanding_report($upto_balance_date, $account_group_id, $for_account = ''){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("xrf.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - xrf.total_amount AS amount, xrf.posting_date as st_date, a.account_id, a.account_name, a.account_mobile, xrf.xrf_id as st_id, a.credit_limit, a.account_group_id");
        } else if($for_account == 'xrf_hm_laser_pl'){
            $this->db->select("xrf.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - xrf.total_amount AS amount, xrf.posting_date as st_date, a.account_id, a.account_name, a.account_mobile, xrf.xrf_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("xrf.*, '0' AS gold_fine, '0' AS silver_fine, xrf.total_amount AS amount, xrf.posting_date as st_date, a.account_id, a.account_name, a.account_mobile, xrf.xrf_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('hallmark_xrf xrf');
        if(in_array(XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = '.XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, 'left');
        } else if($for_account == 'xrf_hm_laser_pl'){
            $this->db->join('account a', 'a.account_id = '.XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, 'left');
        } else {
            $this->db->join('account a', 'a.account_id = xrf.account_id', 'left');
        }
        $this->db->where('xrf.posting_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_opening_for_outstanding_report($upto_balance_date, $account_group_id) {
        $this->db->select("a.*, CONCAT(IF(a.gold_ob_credit_debit = 1, '-', ''), a.opening_balance_in_gold) AS gold_fine, CONCAT(IF(a.silver_ob_credit_debit = 1, '-', ''), a.opening_balance_in_silver) AS silver_fine, CONCAT(IF(a.rupees_ob_credit_debit = 1, '-', ''), a.opening_balance_in_rupees) AS amount, a.created_at as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('account a');
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_sell_items_for_outstanding_report_department($upto_balance_date, $account_group_id) {
        if(PACKAGE_FOR == 'manek'){
            $this->db->select("si.*, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0'), '0') AS gold_fine, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0'), '0') AS silver_fine, IF(`si`.`type` = 1, (si.amount - si.charges_amt), '".ZERO_VALUE."' - (si.amount - si.charges_amt)) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
            if($sell_purchase_type_3_menu == '1' ) {
                $this->db->select("si.*, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0')) AS gold_fine, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0')) AS silver_fine, '0' AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            } else {
                $this->db->select("si.*, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0') AS silver_fine, '0' AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            }
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    function get_sell_with_gst_amount_for_outstanding_report_department($upto_balance_date, $account_group_id) {
        $this->db->select("s.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."'-s.total_amount AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('sell_with_gst s');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '1');
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_metal_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(mpr.metal_payment_receipt = 1, '-', ''), mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(mpr.metal_payment_receipt = 1, '-', ''), mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_fine, mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_fine, mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('metal_payment_receipt mpr');
        $this->db->join('sell s', 's.sell_id = mpr.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = mpr.metal_category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mpr.metal_item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        //print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_stock_transfer_for_outstanding_report_department($upto_balance_date, $account_group_id, $type_sort) {
//        $this->db->select("std.*, IF(`c`.`category_group_id` = 1, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''), std.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''), std.fine), '0') AS silver_fine, '0' AS amount, '".$type_sort."' as type,a.account_id, a.account_name, a.account_mobile, st.transfer_date as st_date, st.stock_transfer_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("std.*, IF(`c`.`category_group_id` = 1, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.fine, std.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.fine, std.fine), '0') AS silver_fine, '0' AS amount, '".$type_sort."' as type,a.account_id, a.account_name, a.account_mobile, st.transfer_date as st_date, st.stock_transfer_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('stock_transfer_detail std');
        $this->db->join('stock_transfer st', 'st.stock_transfer_id = std.stock_transfer_id', 'left');
        $this->db->join('category c', 'c.category_id = std.category_id', 'left');
//        $this->db->join('item_master item', 'item.item_id = std.item_id', 'left');
        if($type_sort == 'ST F'){
            $this->db->join('account a', 'a.account_id = st.from_department', 'left');
        } else if($type_sort == 'ST T'){
            $this->db->join('account a', 'a.account_id = st.to_department', 'left');
        }
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('st.transfer_date <= ',$upto_balance_date);
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_cashbook_for_outstanding_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        //$this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.department_id', 'left');
        $this->db->where('pr.sell_id IS NULL', null, true);
        $this->db->where('pr.other_id IS NULL', null, true);
        $this->db->where('pr.transaction_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
 //       echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_for_outstanding_report_department($upto_balance_date, $account_group_id){
//        $this->db->select("ird.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("ird.*, IF(`c`.`category_group_id` = 1, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.fine, ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.fine, ird.fine), '0') AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->join('category c', 'c.category_id = ird.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ird.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.department_id', 'left');
        $this->db->where('ird.ird_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_silver_for_outstanding_report_department($upto_balance_date, $account_group_id){
        $this->db->select("irsd.*, IF(`c`.`category_group_id` = 1, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.fine, irsd.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.fine, irsd.fine), '0') AS silver_fine, '0' AS amount, irsd.irsd_date as st_date,a.account_id, a.account_name, a.account_mobile, irsd.irsd_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_silver_details irsd');
        $this->db->join('issue_receive_silver irs', 'irs.irs_id = irsd.irs_id', 'left');
        $this->db->join('category c', 'c.category_id = irsd.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = irsd.item_id', 'left');
        $this->db->join('account a', 'a.account_id = irs.department_id', 'left');
        $this->db->where('irsd.irsd_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_manu_hand_made_for_outstanding_report_department($upto_balance_date, $account_group_id){
        $this->db->select("mhm_detail.*, IF(`c`.`category_group_id` = 1, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.fine, mhm_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID."' || `mhm_detail`.`type_id` = '".MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mhm_detail.fine, mhm_detail.fine), '0') AS silver_fine, '0' AS amount, mhm_detail.mhm_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mhm_detail.mhm_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->join('category c', 'c.category_id = mhm_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mhm_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.department_id', 'left');
        $this->db->where('mhm_detail.mhm_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_casting_for_outstanding_report_department($upto_balance_date, $account_group_id){
        $this->db->select("ce_detail.*, IF(`c`.`category_group_id` = 1, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.fine, ce_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID."' || `ce_detail`.`type_id` = '".CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-ce_detail.fine, ce_detail.fine), '0') AS silver_fine, '0' AS amount, ce_detail.ce_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, ce_detail.ce_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->join('category c', 'c.category_id = ce_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ce_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ce.department_id', 'left');
        $this->db->where('ce_detail.ce_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_machin_chain_for_outstanding_report_department($upto_balance_date, $account_group_id){
        $this->db->select("mc_detail.*, IF(`c`.`category_group_id` = 1, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.fine, mc_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID."' || `mc_detail`.`type_id` = '".MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID."', '".ZERO_VALUE."'-mc_detail.fine, mc_detail.fine), '0') AS silver_fine, '0' AS amount, mc_detail.machine_chain_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mc_detail.machine_chain_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('machine_chain_details mc_detail');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mc_detail.machine_chain_id', 'left');
        $this->db->join('category c', 'c.category_id = mc_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mc_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mc.department_id', 'left');
        $this->db->where('mc_detail.machine_chain_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_sell_items_for_outstanding_report_department($upto_balance_date, $account_group_id){
        $this->db->select("oi.*, '0' AS gold_fine, '0' AS silver_fine, IF(`oi`.`type` = 1, '".ZERO_VALUE."'-oi.amount, oi.amount) AS amount, o.department_id AS department_id, oi.other_item_id as id, o.other_date as st_date, o.other_id as st_id,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        $this->db->join('category c', 'c.category_id = oi.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = oi.item_id', 'left');
        $this->db->join('account a', 'a.account_id = o.department_id', 'left');
        $this->db->where('o.other_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, o.other_date as st_date, o.other_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        $this->db->join('account a', 'a.account_id = o.department_id', 'left');
        $this->db->where('o.other_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '1');
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('a.gold_fine !=', '0');
                $this->db->or_where('a.silver_fine !=', '0');
                $this->db->or_where('a.amount !=', '0');
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
    }
    /*********  Outstanding Report Related Function End ***********/


    /*********  Balance Sheet Report Related Function Start *********/
    function get_sell_items_for_balance_sheet_report($upto_balance_date, $account_group_id) {
        if(PACKAGE_FOR == 'manek'){
            $this->db->select("si.*, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0'), '0') AS gold_fine, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0'), '0') AS silver_fine, IF(`si`.`type` = 1, si.amount, '".ZERO_VALUE."'-si.amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
            if($sell_purchase_type_3_menu == '1' ) {
                $this->db->select("si.*, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0')) AS gold_fine, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0')) AS silver_fine, IF(`si`.`type` = 1, si.charges_amt, '".ZERO_VALUE."'-si.charges_amt) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            } else {
                $this->db->select("si.*, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, si.fine, '".ZERO_VALUE."'-si.fine), '0') AS silver_fine, si.charges_amt AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            }
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }

    function get_sell_discount_for_balance_sheet_report($upto_balance_date, $account_group_id) {
        $this->db->select("s.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - s.discount_amount AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('sell s');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }

    function get_payment_receipt_for_balance_sheet_report($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_payment_receipt_for_balance_sheet_report_bank($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.bank_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '2');
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_metal_payment_receipt_for_balance_sheet_report($upto_balance_date, $account_group_id) {
//        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(mpr.metal_payment_receipt = 1, '', '-'), mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(mpr.metal_payment_receipt = 1, '', '-'), mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, IF(mpr.metal_payment_receipt = 1, mpr.metal_fine, '".ZERO_VALUE."'-mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(mpr.metal_payment_receipt = 1, mpr.metal_fine, '".ZERO_VALUE."'-mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('metal_payment_receipt mpr');
        $this->db->join('sell s', 's.sell_id = mpr.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = mpr.metal_category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mpr.metal_item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        //print_r($this->db->last_query());die;
        return $query->result();
    }

    function get_gold_bhav_for_balance_sheet_report($upto_balance_date, $account_group_id) {
//        $this->db->select("gb.*,  CONCAT(IF(gb.gold_sale_purchase = 1, '-', ''), gb.gold_weight) AS gold_fine, CONCAT(IF(gb.gold_sale_purchase = 1, '', '-'), gb.gold_value) AS amount, '0' AS silver_fine, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("gb.*,  IF(gb.gold_sale_purchase = 1, '".ZERO_VALUE."'-gb.gold_weight, gb.gold_weight) AS gold_fine, IF(gb.gold_sale_purchase = 1, gb.gold_value, '".ZERO_VALUE."'-gb.gold_value) AS amount, '0' AS silver_fine, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('gold_bhav gb');
        $this->db->join('sell s', 's.sell_id = gb.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_silver_bhav_for_balance_sheet_report($upto_balance_date, $account_group_id) {
//        $this->db->select("sb.*, CONCAT(IF(sb.silver_sale_purchase = 1, '-', ''), sb.silver_weight) AS silver_fine, '0' AS gold_fine, CONCAT(IF(sb.silver_sale_purchase = 1, '', '-'), sb.silver_value) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("sb.*, IF(sb.silver_sale_purchase = 1, '".ZERO_VALUE."'-sb.silver_weight, sb.silver_weight) AS silver_fine, '0' AS gold_fine, IF(sb.silver_sale_purchase = 1, sb.silver_value, '".ZERO_VALUE."'-sb.silver_value) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('silver_bhav sb');
        $this->db->join('sell s', 's.sell_id = sb.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        echo "<pre>"; print_r($query->result()); exit;
        return $query->result();
    }

    function get_transfer_naam_jama_for_balance_sheet_report($upto_balance_date, $account_id, $type_sort, $account_group_id) {
//        $this->db->select("tr.*, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_gold) AS gold_fine, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_silver) AS silver_fine, CONCAT(IF(tr.naam_jama = 1, '-', ''), tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->select("tr.*, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_gold, tr.transfer_gold) AS gold_fine, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_silver, tr.transfer_silver) AS silver_fine, IF(tr.naam_jama = 1, '".ZERO_VALUE."'-tr.transfer_amount, tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->from('transfer tr');
        $this->db->join('sell s', 's.sell_id = tr.sell_id', 'left');
//        $this->db->join('account a', 'a.account_id = tr.transfer_account_id', 'left');
        $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
//        if(!empty($account_group_id)){
//            $this->db->where('a.account_group_id', $account_group_id);
//        }
        if(!empty($account_id)){
            $this->db->where('s.account_id', $account_id);
        }
        if($type_sort == 'TR Naam'){
            $this->db->where('tr.naam_jama', '1');
        }
        if($type_sort == 'TR Jama'){
            $this->db->where('tr.naam_jama', '2');
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query1 = $this->db->get();
        $result1 = $query1->result();
        
//        $this->db->select("tr.*, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_gold) AS gold_fine, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_silver) AS silver_fine, CONCAT(IF(tr.naam_jama = 2, '-', ''), tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->select("tr.*, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_gold, tr.transfer_gold) AS gold_fine, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_silver, tr.transfer_silver) AS silver_fine, IF(tr.naam_jama = 2, '".ZERO_VALUE."'-tr.transfer_amount, tr.transfer_amount) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, '". $type_sort ."' as type_sort, a.credit_limit, a.account_group_id");
        $this->db->from('transfer tr');
        $this->db->join('sell s', 's.sell_id = tr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = tr.transfer_account_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_id)){
            $this->db->where('tr.transfer_account_id', $account_id);
        }
        if($type_sort == 'TR Naam'){
            $this->db->where('tr.naam_jama', '1');
        }
        if($type_sort == 'TR Jama'){
            $this->db->where('tr.naam_jama', '2');
        }
//        if(!empty($account_group_id)){
//            $this->db->where('a.account_group_id', $account_group_id);
//        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query2 = $this->db->get();
        $result2 = $query2->result();
        
//        echo "<pre>"; print_r($query->result()); exit;
        return array_merge($result1, $result2);
    }

    function get_journal_naam_jama_for_balance_sheet_report($upto_balance_date, $account_group_id) {
//        $this->db->select("jr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(jr.type = 2, '-', ''), jr.amount) AS amount, j.journal_date as st_date, a.account_id, a.account_name, a.account_mobile, j.journal_id, a.credit_limit, a.account_group_id");
        $this->db->select("jr.*, '0' AS gold_fine, '0' AS silver_fine, IF(jr.type = 2, '".ZERO_VALUE."'-jr.amount, jr.amount) AS amount, j.journal_date as st_date, a.account_id, a.account_name, a.account_mobile, j.journal_id, a.credit_limit, a.account_group_id");
        $this->db->from('journal_details jr');
        $this->db->join('journal j', 'j.journal_id = jr.journal_id', 'LEFT');
        $this->db->join('account a', 'a.account_id = jr.account_id', 'LEFT');
        $this->db->where('j.journal_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
 //       echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }

    function get_cashbook_for_balance_sheet_report($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        //$this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.account_id', 'left');
        $this->db->where('pr.sell_id IS NULL', null, true);
        $this->db->where('pr.other_id IS NULL', null, true);
        $this->db->where('pr.transaction_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
 //       echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_for_balance_sheet_report($upto_balance_date, $account_group_id){
//        $this->db->select("ird.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'','-'),ird.fine), '0') AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("ird.*, IF(`ird`.`type_id` = 1, ird.fine, '".ZERO_VALUE."'-ird.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->join('category c', 'c.category_id = ird.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ird.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.worker_id', 'left');
        $this->db->where('ird.ird_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_silver_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $this->db->select("irsd.*, '0' AS gold_fine, IF(`irsd`.`type_id` = 1, irsd.fine, '".ZERO_VALUE."'-irsd.fine) AS silver_fine, '0' AS amount, irsd.irsd_date as st_date,a.account_id, a.account_name, a.account_mobile, irsd.irsd_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_silver_details irsd');
        $this->db->join('issue_receive_silver irs', 'irs.irs_id = irsd.irs_id', 'left');
        $this->db->join('category c', 'c.category_id = irsd.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = irsd.item_id', 'left');
        $this->db->join('account a', 'a.account_id = irs.worker_id', 'left');
        $this->db->where('irsd.irsd_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_manu_hand_made_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $this->db->select("mhm_detail.*, IF((`mhm_detail`.`type_id` = 1 || `mhm_detail`.`type_id` = 2), mhm_detail.fine, '".ZERO_VALUE."'-mhm_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, mhm_detail.mhm_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mhm_detail.mhm_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->join('category c', 'c.category_id = mhm_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mhm_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.worker_id', 'left');
        $this->db->where('mhm_detail.mhm_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_casting_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $this->db->select("ce_detail.*, IF((`ce_detail`.`type_id` = 1 || `ce_detail`.`type_id` = 2), ce_detail.fine, '".ZERO_VALUE."'-ce_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, ce_detail.ce_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, ce_detail.ce_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->join('category c', 'c.category_id = ce_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ce_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ce.worker_id', 'left');
        $this->db->where('ce_detail.ce_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_machin_chain_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $this->db->select("mc_detail.*, IF((`mc_detail`.`type_id` = 1 || `mc_detail`.`type_id` = 2), mc_detail.fine, '".ZERO_VALUE."'-mc_detail.fine) AS gold_fine, '0' AS silver_fine, '0' AS amount, mc_detail.machine_chain_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mc_detail.machine_chain_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('machine_chain_details mc_detail');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mc_detail.machine_chain_id', 'left');
        $this->db->join('category c', 'c.category_id = mc_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mc_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mc.worker_id', 'left');
        $this->db->where('mc_detail.machine_chain_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_sell_items_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $this->db->select("oi.*, '0' AS gold_fine, '0' AS silver_fine, IF(`oi`.`type` = 1, oi.amount, '".ZERO_VALUE."'-oi.amount) AS amount, o.department_id AS department_id, oi.other_item_id as id, o.other_date as st_date, o.other_id as st_id,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        $this->db->join('category c', 'c.category_id = oi.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = oi.item_id', 'left');
        $this->db->join('account a', 'a.account_id = o.account_id', 'left');
        $this->db->where('o.other_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_payment_receipt_for_balance_sheet_report($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '', '-'), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, pr.amount, '".ZERO_VALUE."'-pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, o.other_date as st_date, o.other_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        $this->db->join('account a', 'a.account_id = o.account_id', 'left');
        $this->db->where('o.other_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_other_payment_receipt_for_balance_sheet_report_bank($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, o.other_date as st_date, o.other_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.bank_id', 'left');
        $this->db->where('o.other_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '2');
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_sell_ad_charges_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("ad_c.*, '0' AS gold_fine, '0' AS silver_fine, '".ZERO_VALUE."' - ad_c.ad_amount AS amount, s.sell_date as st_date, a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("ad_c.*, '0' AS gold_fine, '0' AS silver_fine, ad_c.ad_amount AS amount, s.sell_date as st_date, a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('sell_ad_charges ad_c');
        $this->db->join('sell s', 's.sell_id = ad_c.sell_id', 'left');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = '.MF_LOSS_EXPENSE_ACCOUNT_ID, 'left');
        } else {
            $this->db->join('account a', 'a.account_id = s.account_id', 'left');
        }
        $this->db->where('s.sell_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_fine_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("wh.*, '".ZERO_VALUE."'-wh.fine AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("wh.*, wh.fine AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab wh');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module !=',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_done_ir_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("whd.*, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS gold_fine, '0' AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab_detail whd');
        $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module !=',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_hisab_fine_silver_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("wh.*, '0' AS gold_fine, '".ZERO_VALUE."'-wh.fine AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("wh.*, '0' AS gold_fine, wh.fine AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, wh.worker_hisab_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab wh');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_hisab_done_irs_for_balance_sheet_report($upto_balance_date, $account_group_id){
        $account_ids = $this->get_account_ids_from_account_group_id($account_group_id);
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->select("whd.*, '0' AS gold_fine, IF(`whd`.`type_id` = 1, '".ZERO_VALUE."'-whd.fine_adjusted, whd.fine_adjusted) AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $this->db->select("whd.*, '0' AS gold_fine, IF(`whd`.`type_id` = 1, whd.fine_adjusted, '".ZERO_VALUE."'-whd.fine_adjusted) AS silver_fine, '0' AS amount, wh.hisab_date as st_date, a.account_id, a.account_name, a.account_mobile, whd.wsd_id as st_id, a.credit_limit, a.account_group_id");
        }
        $this->db->from('worker_hisab_detail whd');
        $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
        if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
            $this->db->join('account a', 'a.account_id = wh.against_account_id', 'left');
            $this->db->join('account a_w', 'a_w.account_id = wh.worker_id', 'left');
        } else {
            $this->db->join('account a', 'a.account_id = wh.worker_id', 'left');
            $this->db->join('account a_mf', 'a_mf.account_id = wh.against_account_id', 'left');
        }
        $this->db->where('wh.hisab_date <=',$upto_balance_date);
        $this->db->where('wh.is_module',HISAB_DONE_IS_MODULE_MIR_SILVER);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_opening_for_balance_sheet_report($upto_balance_date, $account_group_id) {
        $this->db->select("a.*, CONCAT(IF(a.gold_ob_credit_debit = 1, '-', ''), a.opening_balance_in_gold) AS gold_fine, CONCAT(IF(a.silver_ob_credit_debit = 1, '-', ''), a.opening_balance_in_silver) AS silver_fine, CONCAT(IF(a.rupees_ob_credit_debit = 1, '-', ''), a.opening_balance_in_rupees) AS amount, a.created_at as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id,'true' as is_opening_balance");
        $this->db->from('account a');
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_sell_items_for_balance_sheet_report_department($upto_balance_date, $account_group_id) {
        if(PACKAGE_FOR == 'manek'){
            $this->db->select("si.*, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0'), '0') AS gold_fine, IF(`si`.`spi_rate` = 0, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0'), '0') AS silver_fine, IF(`si`.`type` = 1, (si.amount - si.charges_amt), '".ZERO_VALUE."' - (si.amount - si.charges_amt)) AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        } else {
            $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
            if($sell_purchase_type_3_menu == '1' ) {
                $this->db->select("si.*, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0')) AS gold_fine, IF(`si`.`spi_rate_of` = 2 AND `si`.`spi_rate` != 0, '0', IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0')) AS silver_fine, '0' AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            } else {
                $this->db->select("si.*, IF(`c`.`category_group_id` = 1, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`si`.`type` = 1, '".ZERO_VALUE."'-si.fine, si.fine), '0') AS silver_fine, '0' AS amount, s.sell_date as st_date,a.account_id, a.account_name, a.account_mobile, s.sell_id as st_id, a.credit_limit, a.account_group_id");
            }
        }
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = si.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = si.item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_payment_receipt_for_balance_sheet_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '1');
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_metal_payment_receipt_for_balance_sheet_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(mpr.metal_payment_receipt = 1, '-', ''), mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(mpr.metal_payment_receipt = 1, '-', ''), mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("mpr.*, IF(`c`.`category_group_id` = 1, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_fine, mpr.metal_fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(mpr.metal_payment_receipt = 1, '".ZERO_VALUE."'-mpr.metal_fine, mpr.metal_fine), '0') AS silver_fine, '0' AS amount, mpr.metal_payment_receipt as type,a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('metal_payment_receipt mpr');
        $this->db->join('sell s', 's.sell_id = mpr.sell_id', 'left');
        $this->db->join('category c', 'c.category_id = mpr.metal_category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mpr.metal_item_id', 'left');
        $this->db->join('account a', 'a.account_id = s.process_id', 'left');
        $this->db->where('s.sell_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        //print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_stock_transfer_for_balance_sheet_report_department($upto_balance_date, $account_group_id, $type_sort) {
//        $this->db->select("std.*, IF(`c`.`category_group_id` = 1, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''), std.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF('".$type_sort."' = 'ST F', '-', ''), std.fine), '0') AS silver_fine, '0' AS amount, '".$type_sort."' as type,a.account_id, a.account_name, a.account_mobile, st.transfer_date as st_date, st.stock_transfer_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("std.*, IF(`c`.`category_group_id` = 1, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.fine, std.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF('".$type_sort."' = 'ST F', '".ZERO_VALUE."'-std.fine, std.fine), '0') AS silver_fine, '0' AS amount, '".$type_sort."' as type,a.account_id, a.account_name, a.account_mobile, st.transfer_date as st_date, st.stock_transfer_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('stock_transfer_detail std');
        $this->db->join('stock_transfer st', 'st.stock_transfer_id = std.stock_transfer_id', 'left');
        $this->db->join('category c', 'c.category_id = std.category_id', 'left');
//        $this->db->join('item_master item', 'item.item_id = std.item_id', 'left');
        if($type_sort == 'ST F'){
            $this->db->join('account a', 'a.account_id = st.from_department', 'left');
        } else if($type_sort == 'ST T'){
            $this->db->join('account a', 'a.account_id = st.to_department', 'left');
        }
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('st.transfer_date <= ',$upto_balance_date);
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result();
    }
    
    function get_cashbook_for_balance_sheet_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, '0' AS gold_fine, '0' AS silver_fine, pr.transaction_date as st_date,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        //$this->db->join('sell s', 's.sell_id = pr.sell_id', 'left');
        $this->db->join('account a', 'a.account_id = pr.department_id', 'left');
        $this->db->where('pr.sell_id IS NULL', null, true);
        $this->db->where('pr.other_id IS NULL', null, true);
        $this->db->where('pr.transaction_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
 //       echo "<pre>"; print_r($this->db->last_query()); exit;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_for_balance_sheet_report_department($upto_balance_date, $account_group_id){
//        $this->db->select("ird.*, IF(`c`.`category_group_id` = 1, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, CONCAT(IF(`ird`.`type_id` = 1,'-',''),ird.fine), '0') AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("ird.*, IF(`c`.`category_group_id` = 1, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.fine, ird.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`ird`.`type_id` = 1, '".ZERO_VALUE."'-ird.fine, ird.fine), '0') AS silver_fine, '0' AS amount, ird.ird_date as st_date,a.account_id, a.account_name, a.account_mobile, ird.ird_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->join('category c', 'c.category_id = ird.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ird.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ir.department_id', 'left');
        $this->db->where('ird.ird_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_issue_receive_silver_for_balance_sheet_report_department($upto_balance_date, $account_group_id){
        $this->db->select("irsd.*, IF(`c`.`category_group_id` = 1, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.fine, irsd.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF(`irsd`.`type_id` = 1, '".ZERO_VALUE."'-irsd.fine, irsd.fine), '0') AS silver_fine, '0' AS amount, irsd.irsd_date as st_date,a.account_id, a.account_name, a.account_mobile, irsd.irsd_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('issue_receive_silver_details irsd');
        $this->db->join('issue_receive_silver irs', 'irs.irs_id = irsd.irs_id', 'left');
        $this->db->join('category c', 'c.category_id = irsd.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = irsd.item_id', 'left');
        $this->db->join('account a', 'a.account_id = irs.department_id', 'left');
        $this->db->where('irsd.irsd_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_manu_hand_made_for_balance_sheet_report_department($upto_balance_date, $account_group_id){
        $this->db->select("mhm_detail.*, IF(`c`.`category_group_id` = 1, IF((`mhm_detail`.`type_id` = 1 || `mhm_detail`.`type_id` = 2), '".ZERO_VALUE."'-mhm_detail.fine, mhm_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF((`mhm_detail`.`type_id` = 1 || `mhm_detail`.`type_id` = 2), '".ZERO_VALUE."'-mhm_detail.fine, mhm_detail.fine), '0') AS silver_fine, '0' AS amount, mhm_detail.mhm_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mhm_detail.mhm_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->join('category c', 'c.category_id = mhm_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mhm_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mhm.department_id', 'left');
        $this->db->where('mhm_detail.mhm_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_casting_for_balance_sheet_report_department($upto_balance_date, $account_group_id){
        $this->db->select("ce_detail.*, IF(`c`.`category_group_id` = 1, IF((`ce_detail`.`type_id` = 1 || `ce_detail`.`type_id` = 2), '".ZERO_VALUE."'-ce_detail.fine, ce_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF((`ce_detail`.`type_id` = 1 || `ce_detail`.`type_id` = 2), '".ZERO_VALUE."'-ce_detail.fine, ce_detail.fine), '0') AS silver_fine, '0' AS amount, ce_detail.ce_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, ce_detail.ce_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->join('category c', 'c.category_id = ce_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = ce_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = ce.department_id', 'left');
        $this->db->where('ce_detail.ce_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_manufacture_machin_chain_for_balance_sheet_report_department($upto_balance_date, $account_group_id){
        $this->db->select("mc_detail.*, IF(`c`.`category_group_id` = 1, IF((`mc_detail`.`type_id` = 1 || `mc_detail`.`type_id` = 2), '".ZERO_VALUE."'-mc_detail.fine, mc_detail.fine), '0') AS gold_fine, IF(`c`.`category_group_id` = 2, IF((`mc_detail`.`type_id` = 1 || `mc_detail`.`type_id` = 2), '".ZERO_VALUE."'-mc_detail.fine, mc_detail.fine), '0') AS silver_fine, '0' AS amount, mc_detail.machine_chain_detail_date as st_date,a.account_id, a.account_name, a.account_mobile, mc_detail.machine_chain_detail_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('machine_chain_details mc_detail');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mc_detail.machine_chain_id', 'left');
        $this->db->join('category c', 'c.category_id = mc_detail.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = mc_detail.item_id', 'left');
        $this->db->join('account a', 'a.account_id = mc.department_id', 'left');
        $this->db->where('mc_detail.machine_chain_detail_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_sell_items_for_balance_sheet_report_department($upto_balance_date, $account_group_id){
        $this->db->select("oi.*, '0' AS gold_fine, '0' AS silver_fine, IF(`oi`.`type` = 1, '".ZERO_VALUE."'-oi.amount, oi.amount) AS amount, o.department_id AS department_id, oi.other_item_id as id, o.other_date as st_date, o.other_id as st_id,a.account_id, a.account_name, a.account_mobile, a.credit_limit, a.account_group_id");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        $this->db->join('category c', 'c.category_id = oi.category_id', 'left');
        $this->db->join('item_master item', 'item.item_id = oi.item_id', 'left');
        $this->db->join('account a', 'a.account_id = o.department_id', 'left');
        $this->db->where('o.other_date <=',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
//        print_r($this->db->last_query());die;
        return $query->result();
    }
    
    function get_other_payment_receipt_for_balance_sheet_report_department($upto_balance_date, $account_group_id) {
//        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, CONCAT(IF(pr.payment_receipt = 1, '-', ''), pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, s.sell_date as st_date, s.sell_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->select("pr.*, '0' AS gold_fine, '0' AS silver_fine, IF(pr.payment_receipt = 1, '".ZERO_VALUE."'-pr.amount, pr.amount) AS amount, a.account_id, a.account_name, a.account_mobile, o.other_date as st_date, o.other_id as st_id, a.credit_limit, a.account_group_id");
        $this->db->from('payment_receipt pr');
        $this->db->join('other o', 'o.other_id = pr.other_id', 'left');
        $this->db->join('account a', 'a.account_id = o.department_id', 'left');
        $this->db->where('o.other_date <= ',$upto_balance_date);
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        $this->db->where('pr.cash_cheque', '1');
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('a.gold_fine !=', '0');
//                $this->db->or_where('a.silver_fine !=', '0');
//                $this->db->or_where('a.amount !=', '0');
//            $this->db->group_end();
//        }
        $query = $this->db->get();
        return $query->result();
    }
    function get_account_for_balance_sheet($upto_balance_date){
    	$account_group_ids = $this->applib->current_user_account_group_ids();
    	$account_ids = $this->applib->current_user_account_ids();
    	
		$this->db->select('account_id, credit_limit');
		$this->db->from('account');
//        if($upto_balance_date == $this->today_date){
//            $this->db->group_start();
//                $this->db->or_where('gold_fine !=', '0');
//                $this->db->or_where('silver_fine !=', '0');
//                $this->db->or_where('amount !=', '0');
//            $this->db->group_end();
//        }
        if(!empty($account_group_ids)){
            $this->db->where_in('account_group_id', $account_group_ids);
        } else {
            $this->db->where_in('account_group_id', array(-1));
        }

        if($account_ids == "allow_all_accounts") {
            
        } elseif(!empty($account_ids)) {
            $this->db->where_in('account_id', $account_ids);
        } else {
            $this->db->where_in('account_id', array(-1));
        }

		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
    
    /*********  Balance Sheet Report Related Function End ***********/
    
    function get_purchase_items_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("si.sell_item_id, si.grwt, si.created_at, 'P' AS type");
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        $this->db->where('si.type !=', '1');
        if(!empty($category_id)){
            $this->db->where('si.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('si.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('si.touch_id', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_total_sell_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("SUM(si.grwt) AS grwt");
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        $this->db->where('si.type', '1');
        if(!empty($category_id)){
            $this->db->where('si.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('si.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('si.touch_id', $touch_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("SUM(std.grwt) AS grwt");
        $this->db->from('stock_transfer_detail std');
        $this->db->join('stock_transfer st', 'st.stock_transfer_id = std.stock_transfer_id', 'left');
        if(!empty($department_id)){
            $this->db->where('st.from_department', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('std.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('std.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('std.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_metal_items_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("m.metal_pr_id, m.metal_grwt AS grwt, m.created_at, 'M' AS type");
        $this->db->from('metal_payment_receipt m');
        $this->db->join('sell s', 's.sell_id = m.sell_id', 'left');
        $this->db->where('m.metal_payment_receipt', '2');
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('m.metal_category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('m.metal_item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('m.metal_tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("std.transfer_detail_id, std.grwt AS grwt, std.created_at, 'ST' AS type");
        $this->db->from('stock_transfer_detail std');
        $this->db->join('stock_transfer st', 'st.stock_transfer_id = std.stock_transfer_id', 'left');
        if(!empty($department_id)){
            $this->db->where('st.to_department', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('std.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('std.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('std.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_total_metal_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("SUM(m.metal_grwt) AS grwt");
        $this->db->from('metal_payment_receipt m');
        $this->db->join('sell s', 's.sell_id = m.sell_id', 'left');
        $this->db->where('m.metal_payment_receipt', '1');
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('m.metal_category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('m.metal_item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('m.metal_tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("SUM(ird.weight) AS grwt");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        $this->db->where('ird.type_id', '1');
        if(!empty($department_id)){
            $this->db->where('ir.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('ird.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ird.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('ird.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("ird.ird_id, ird.weight AS grwt, ird.created_at, 'R' AS type");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        if(!empty($department_id)){
            $this->db->where('ir.department_id', $department_id);
        }
        $this->db->where('ird.type_id !=', '1');
        if(!empty($category_id)){
            $this->db->where('ird.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ird.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('ird.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    
    function get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("SUM(mhm_detail.weight) AS grwt");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        $this->db->where_in('mhm_detail.type_id', array(MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID, MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID));
        if(!empty($department_id)){
            $this->db->where('mhm.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('mhm_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mhm_detail.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('mhm_detail.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("mhm_detail.mhm_detail_id, mhm_detail.weight AS grwt, mhm_detail.created_at, 'MHM_R' AS type");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        if(!empty($department_id)){
            $this->db->where('mhm.department_id', $department_id);
        }
        $this->db->where_in('mhm_detail.type_id', array(MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID, MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID));
        if(!empty($category_id)){
            $this->db->where('mhm_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mhm_detail.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('mhm_detail.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_total_machine_chain_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("SUM(mcd.weight) AS grwt");
        $this->db->from('machine_chain_details mcd');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mcd.machine_chain_id', 'left');
        $this->db->where_in('mcd.type_id', array(MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID, MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID));
        if(!empty($department_id)){
            $this->db->where('mc.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('mcd.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mcd.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('mcd.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_machine_chain_receive_items_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("mcd.machine_chain_id, mcd.weight AS grwt, mcd.created_at, 'MC_R' AS type");
        $this->db->from('machine_chain_details mcd');
        $this->db->join('machine_chain mc', 'mc.machine_chain_id = mcd.machine_chain_id', 'left');
        if(!empty($department_id)){
            $this->db->where('mc.department_id', $department_id);
        }
        $this->db->where_in('mcd.type_id', array(MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID, MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID));
        if(!empty($category_id)){
            $this->db->where('mcd.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mcd.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('mcd.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_total_other_sell_grwt($department_id, $category_id, $item_id){
        $this->db->select("SUM(oi.grwt) AS grwt");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        $this->db->where('oi.type', '1');
        if(!empty($department_id)){
            $this->db->where('o.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('oi.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('oi.item_id', $item_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_other_purchase_items_grwt($department_id, $category_id, $item_id){
        $this->db->select("oi.other_item_id, oi.grwt AS grwt, oi.created_at, 'O P' AS type");
        $this->db->from('other_items oi');
        $this->db->join('other o', 'o.other_id = oi.other_id', 'left');
        if(!empty($department_id)){
            $this->db->where('o.department_id', $department_id);
        }
        $this->db->where('oi.type', '2');
        if(!empty($category_id)){
            $this->db->where('oi.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('oi.item_id', $item_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_used_stock_from_sell($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("s.sell_id");
        $this->db->from('sell s');
        $this->db->join('sell_items si', 'si.sell_id = s.sell_id', 'left');
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('si.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('si.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('si.touch_id', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_used_stock_from_metal($department_id, $category_id, $item_id, $touch_id){
        
        $this->db->select("m.metal_pr_id");
        $this->db->from('metal_payment_receipt m');
        $this->db->join('sell s', 's.sell_id = m.sell_id', 'left');
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('m.metal_category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('m.metal_item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('m.metal_tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_stock_transfer_to_sell($department_id, $category_id, $item_id, $touch_id, $purchase_sell_item_id){
        $this->db->select("si.sell_item_id,si.grwt");
        $this->db->from('sell_items si');
        $this->db->join('sell s', 's.sell_id = si.sell_id', 'left');
        if(!empty($department_id)){
            $this->db->where('s.process_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('si.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('si.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('si.touch_id', $touch_id);
        }
        if(!empty($purchase_sell_item_id)){
            $this->db->where('si.purchase_sell_item_id', $purchase_sell_item_id);
        }
        $this->db->where('si.stock_type', '3');
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_stock_transfer_to_issue_receive($department_id, $category_id, $item_id, $touch_id, $purchase_sell_item_id){
        $this->db->select("ird.ird_id,ird.weight as grwt");
        $this->db->from('issue_receive_details ird');
        $this->db->join('issue_receive ir', 'ir.ir_id = ird.ir_id', 'left');
        if(!empty($department_id)){
            $this->db->where('ir.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('ird.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ird.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('ird.tunch', $touch_id);
        }
        if(!empty($purchase_sell_item_id)){
            $this->db->where('ird.purchase_sell_item_id', $purchase_sell_item_id);
        }
        $this->db->where('ird.stock_type', '3');
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_stock_transfer_to_manu_hand_made($department_id, $category_id, $item_id, $touch_id, $purchase_sell_item_id){
        $this->db->select("mhm_detail.mhm_detail_id,mhm_detail.weight as grwt");
        $this->db->from('manu_hand_made_details mhm_detail');
        $this->db->join('manu_hand_made mhm', 'mhm.mhm_id = mhm_detail.mhm_id', 'left');
        if(!empty($department_id)){
            $this->db->where('mhm.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('mhm_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mhm_detail.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('mhm_detail.tunch', $touch_id);
        }
        if(!empty($purchase_sell_item_id)){
            $this->db->where('mhm_detail.purchase_sell_item_id', $purchase_sell_item_id);
        }
        $this->db->where('mhm_detail.stock_type', '3');
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_used_stock_from_issue_receive($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("ir.ir_id");
        $this->db->from('issue_receive ir');
        $this->db->join('issue_receive_details ird', 'ird.ir_id = ir.ir_id', 'left');
        if(!empty($department_id)){
            $this->db->where('ir.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('ird.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ird.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('ird.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_used_stock_from_manu_hand_made($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("mhm.mhm_id");
        $this->db->from('manu_hand_made mhm');
        $this->db->join('manu_hand_made_details mhm_detail', 'mhm_detail.mhm_id = mhm.mhm_id', 'left');
        if(!empty($department_id)){
            $this->db->where('mhm.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('mhm_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('mhm_detail.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('mhm_detail.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_used_stock_from_machine_chain($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("machine_chain.machine_chain_id");
        $this->db->from('machine_chain machine_chain');
        $this->db->join('machine_chain_details machine_chain_detail', 'machine_chain_detail.machine_chain_id = machine_chain.machine_chain_id', 'left');
        if(!empty($department_id)){
            $this->db->where('machine_chain.department_id', $department_id);
        }
        if(!empty($category_id)){
            $this->db->where('machine_chain_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('machine_chain_detail.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('machine_chain_detail.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function check_is_exists($table_name,$where_array)
    {
        $query = $this->db->get_where($table_name,$where_array);
        if ($query->num_rows() > 0){
            return true;
        }
        return false;
    }
    
    function get_account_for_outstanding($upto_balance_date){
		$this->db->select('account_id, credit_limit');
		$this->db->from('account');
        if($upto_balance_date == $this->today_date){
            $this->db->group_start();
                $this->db->or_where('gold_fine !=', '0');
                $this->db->or_where('silver_fine !=', '0');
                $this->db->or_where('amount !=', '0');
            $this->db->group_end();
        }
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
    
    function get_item_ids_by_category_group($category_group_id){
        $this->db->select("item.item_id");
        $this->db->from('item_master item');
        $this->db->join('category c', 'c.category_id = item.category_id', 'left');
        $this->db->where('c.category_group_id', $category_group_id);
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->result_array();
    }
    
    function get_next_machine_chain_operation_data($operation_id){
        $operation_sequence_no = $this->get_column_value_by_id('machine_chain_operation', 'sequence_no', array('operation_id' => $operation_id));
        $this->db->select("*");
        $this->db->from('machine_chain_operation');
        $this->db->where('sequence_no > ', $operation_sequence_no);
        $this->db->order_by('sequence_no', 'ASC');
        $this->db->limit('1');
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        return $query->row();
    }
    
    function get_total_casting_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("SUM(ce_detail.weight) AS grwt");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        $this->db->where_in('ce_detail.type_id', array(CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID, CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID));
        if(!empty($category_id)){
            $this->db->where('ce_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ce_detail.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('ce_detail.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->row()->grwt;
    }
    
    function get_casting_receive_items_grwt($department_id, $category_id, $item_id, $touch_id){
        $this->db->select("ce_detail.ce_detail_id, ce_detail.weight AS grwt, ce_detail.created_at, 'CASTING_R' AS type");
        $this->db->from('casting_entry_details ce_detail');
        $this->db->join('casting_entry ce', 'ce.ce_id = ce_detail.ce_id', 'left');
        
        $this->db->where_in('ce_detail.type_id', array(CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID, CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID));
        if(!empty($category_id)){
            $this->db->where('ce_detail.category_id', $category_id);
        }
        if(!empty($item_id)){
            $this->db->where('ce_detail.item_id', $item_id);
        }
        if(!empty($touch_id)){
            $this->db->where('ce_detail.tunch', $touch_id);
        }
        $query = $this->db->get();
        return $query->result();
    }

}
