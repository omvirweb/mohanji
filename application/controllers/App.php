<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class App
 * &@property AppModel $app_model
 * &@property AppLib $applib
 * &@property Crud $crud
 */
class App extends CI_Controller{
    function __construct(){
            parent::__construct();
            $this->load->model('Appmodel', 'app_model');
            $this->load->model('Crud', 'crud');
            if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
                redirect('/auth/login/');
            }
            $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
    }

    /**
 * @param $table_name
 * @param $id_column
 * @param $text_column
 * @param $search
 * @param int $page
 * @param array $where
 * @return array
 */
    function get_select2_data($table_name, $id_column, $text_column, $search, $page = 1, $where = array()){
        $party_select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("$id_column,$text_column");
        $this->db->from("$table_name");
        if (!empty($where)) {
            $this->db->where($where);
        }
        if($table_name == 'item_master'){
            $this->db->like("$text_column", $search, 'after');
        } else {
            $this->db->like("$text_column", $search);
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("$text_column");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $party_select2_data[] = array(
                    'id' => $row->$id_column,
                    'text' => $row->$text_column,
                );
            }
        }
            return $party_select2_data;
    }

    function get_select2_data_first_letter($table_name, $id_column, $text_column, $search, $page = 1, $where = array()){
            $party_select2_data = array();
            $resultCount = 10;
            $offset = ($page - 1) * $resultCount;
            $this->db->select("$id_column,$text_column");
            $this->db->from("$table_name");
            if (!empty($where)) {
                    $this->db->where($where);
            }
            $this->db->like("$text_column", $search);
            $this->db->limit($resultCount, $offset);
            $this->db->order_by("$text_column");
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                            $party_select2_data[] = array(
                                    'id' => $row->$id_column,
                $string = $row->$text_column,
                                    'text' => $string[0],
                            );
                    }
            }
            return $party_select2_data;
    }

    /**
 * @param $table_name
 * @param $id_column
 * @param $text_column
 * @param $id_column_val
 */
    function get_select2_first_letter_text_by_id($table_name, $id_column, $text_column, $id_column_val){
		$this->db->select("$id_column,$text_column");
		$this->db->from("$table_name");
		$this->db->where($id_column, $id_column_val);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
            $string = $query->row()->$text_column;
			echo json_encode(array('success' => true, 'id' => $id_column_val, 'text' => $string[0]));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
		exit();
	}
    
    function get_select2_text_by_id($table_name, $id_column, $text_column, $id_column_val){
		$this->db->select("$id_column,$text_column");
		$this->db->from("$table_name");
		$this->db->where($id_column, $id_column_val);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			echo json_encode(array('success' => true, 'id' => $id_column_val, 'text' => $query->row()->$text_column));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
		exit();
	}

    function get_join_select2_data($table_name, $id_column, $text_column, $join_id, $join_table_name, $join_id_column, $join_text_column, $search, $page = 1, $where = array()){
		$party_select2_data = array();
		$resultCount = 10;
		$offset = ($page - 1) * $resultCount;
		$this->db->select('`'.$table_name.'`.`'.$id_column.'`, `'.$table_name.'`.`'.$text_column.'`, `'.$join_table_name.'`.`'.$join_text_column.'`');
		$this->db->from($table_name);
		$this->db->join('`'.$join_table_name.'`', '`'.$table_name.'`.`'.$join_id.'` = `'.$join_table_name.'`.`'.$join_id_column.'`');
		if (!empty($where)) {
			$this->db->where($where);
		}
		$this->db->like("$text_column", $search);
		$this->db->limit($resultCount, $offset);
		$this->db->order_by("$text_column");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$party_select2_data[] = array(
					'id' => $row->$id_column,
					'text' => $row->$join_text_column . ' : ' . $row->$text_column,
				);
			}
		}
		return $party_select2_data;
	}
    
    function get_join_by_id_select2_data($table_name, $id_column, $join_id, $join_table_name, $join_id_column, $join_text_column, $search, $page = 1, $where = array()){
		$party_select2_data = array();
		$resultCount = 10;
		$offset = ($page - 1) * $resultCount;
		$this->db->select('`'.$table_name.'`.`'.$id_column.'`, `'.$join_table_name.'`.`'.$join_text_column.'`');
		$this->db->from($table_name);
		$this->db->join('`'.$join_table_name.'`', '`'.$table_name.'`.`'.$join_id.'` = `'.$join_table_name.'`.`'.$join_id_column.'`');
		if (!empty($where)) {
			$this->db->where($where);
		}
		$this->db->like("$id_column", $search);
		$this->db->limit($resultCount, $offset);
		$this->db->order_by("$id_column");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$party_select2_data[] = array(
					'id' => $row->$id_column,
					'text' => $row->$join_text_column,
				);
			}
		}
		return $party_select2_data;
	}
    
    function get_join_select2_text_by_id($table_name, $id_column, $text_column, $join_id, $join_table_name, $join_id_column, $join_text_column, $id_column_val){
		$this->db->select('`'.$table_name.'`.`'.$id_column.'`, `'.$table_name.'`.`'.$text_column.'`, `'.$join_table_name.'`.`'.$join_text_column.'`');
		$this->db->from($table_name);
		$this->db->join('`'.$join_table_name.'`', '`'.$table_name.'`.`'.$join_id.'` = `'.$join_table_name.'`.`'.$join_id_column.'`');
		$this->db->where($id_column, $id_column_val);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			echo json_encode(array('success' => true, 'id' => $id_column_val, 'text' => $query->row()->$join_text_column . ' : ' . $query->row()->$text_column));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
		exit();
	}
       
    /**
 * @param $table_name
 * @param $id_column
 * @param $text_column
 * @param $search
 * @param array $where
 * @return mixed
 */
    function count_select2_data($table_name, $id_column, $text_column, $search, $where = array()){
		$this->db->select("$id_column");
		$this->db->from("$table_name");
		if (!empty($where)) {
			$this->db->where($where);
		}
		$this->db->like("$text_column", $search, 'after');
		$query = $this->db->get();
		return $query->num_rows();
	}
    
    function touch_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$results = array(
			"results" => $this->get_select2_data('carat', 'carat_id', 'purity', $search, $page),
			"total_count" => $this->count_select2_data('carat', 'carat_id', 'purity', $search),
		);
		echo json_encode($results);
		exit();
	}
        
    function touch_xrf_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = 'show_in_xrf =1 ';
        $results = array(
            "results" => $this->get_select2_data('carat', 'carat_id', 'purity', $search, $page, $where),
            "total_count" => $this->count_select2_data('carat', 'carat_id', 'purity', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function touch_purity_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$results = array(
			"results" => $this->get_select2_data('carat', 'purity', 'purity', $search, $page),
			"total_count" => $this->count_select2_data('carat', 'purity', 'purity', $search),
		);
		echo json_encode($results);
		exit();
	}
        
    function set_touch_select2_val_by_id($id){
            $this->get_select2_text_by_id('carat', 'carat_id', 'purity', $id);
    }
    
    function set_touch_exchange_select2_val_by_id($id){
		$this->db->select("carat_id,purity");
		$this->db->from('carat');
		$this->db->where('carat_id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			echo json_encode(array('success' => true, 'id' => $id, 'text' => $query->row()->purity));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => $id, 'text' => $id));
		exit();
	}
        
    function process_master_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $department_ids = $this->applib->current_user_department_ids();
        $department_ids = implode(',', $department_ids);
        $where = 'account_group_id = '.DEPARTMENT_GROUP.' AND account_id IN ('. $department_ids.')';
        $results = array(
                "results" => $this->get_select2_data('account', 'account_id', 'account_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search, $where),
        );
        echo json_encode($results);
        exit();
    }
    
    function department_select2_source(){
        $department_ids = $this->applib->current_user_department_ids();
		$search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.account_group_id', DEPARTMENT_GROUP);
        $this->db->where_in('account_id', $department_ids);
        $this->db->like("a.account_name", $search);
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
        echo json_encode($results);
        exit();
	}
        
    function order_department_select2_source(){
        $department_ids = $this->applib->current_user_order_department_ids();
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.account_group_id', DEPARTMENT_GROUP);
        $this->db->where_in('account_id', $department_ids);
        $this->db->like("a.account_name", $search);
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function department_from_stock_select2_source(){
        $department_ids = $this->applib->current_user_department_ids();
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->distinct();
        $this->db->select("a.account_id, a.account_name",$search,$page);
        $this->db->from("account a");
        $this->db->join('item_stock i', 'i.department_id = a.account_id');
        $this->db->where('a.account_group_id', DEPARTMENT_GROUP);
        $this->db->where('i.grwt !=', '0');
        $this->db->where_in('account_id', $department_ids);
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name,
                );
            }
        }
//        echo $this->db->last_query();
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
        echo json_encode($results);
        exit();
	}
    
    function process_master_from_process_select2_source($from_department = ''){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
        $department_ids = $this->applib->current_user_department_ids();
        $department_ids = implode(',', $department_ids);
//        $where = 'account_group_id = '.DEPARTMENT_GROUP.' AND account_id != '.$from_department.' AND account_id IN ('. $department_ids.')';
        $where = 'account_group_id = '.DEPARTMENT_GROUP.' AND account_id != '.$from_department.' ';
		$results = array(
			"results" => $this->get_select2_data('account', 'account_id', 'account_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
		);
		echo json_encode($results);
		exit();
	}
    
    function set_process_master_select2_val_by_id($id){
		$this->get_select2_text_by_id('account', 'account_id', 'account_name', $id);
	}   
    
    function item_name_select2_source(){
        $this->db->_protect_identifiers = false;
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("item_id,item_name",$search,$page);
        $this->db->from("item_master");
        $this->db->where('item_name !=', null);
        $this->db->like("item_name", $search);
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("-sequence_no", "DESC");
        $this->db->order_by("item_name", "ASC");
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->item_id,
                    'text' => $row->item_name,
                );
            }
        }
        $this->db->_protect_identifiers=true;
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function silver_item_name_select2_source(){
        $this->db->_protect_identifiers = false;
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("i.item_id,i.item_name",$search,$page);
        $this->db->from("item_master i");
        $this->db->join('category c', 'c.category_id = i.category_id');
        $this->db->where('c.category_group_id', CATEGORY_GROUP_SILVER_ID);
        $this->db->where('i.item_name !=', null);
        $this->db->like("i.item_name", $search);
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("-i.sequence_no", "DESC");
        $this->db->order_by("i.item_name", "ASC");
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->item_id,
                    'text' => $row->item_name,
                );
            }
        }
        $this->db->_protect_identifiers=true;
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search),
        );
        echo json_encode($results);
        exit();
    }

    function set_item_name_select2_val_by_id($id) {
        $this->get_select2_text_by_id('item_master', 'item_id', 'item_name', $id);
    }
    
    function hallmark_item_name_select2_source(){
        $this->db->_protect_identifiers = false;
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("item_id,item_name",$search,$page);
        $this->db->from("hallmark_item_master");
        $this->db->where('item_name !=', null);
        $this->db->like("item_name", $search);
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("item_name", "ASC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->item_id,
                    'text' => $row->item_name,
                );
            }
        }
        $this->db->_protect_identifiers=true;
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('hallmark_item_master', 'item_id', 'item_name', $search),
        );
        echo json_encode($results);
        exit();
    }
        
    function set_hallmark_item_name_select2_val_by_id($id){
        $this->get_select2_text_by_id('hallmark_item_master', 'item_id', 'item_name', $id);
    }
    
    function party_name_with_number_for_order_select2_source() {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $order_display_only_assigned_account = $this->crud->get_column_value_by_id('user_master', 'order_display_only_assigned_account', array('user_id' => $this->logged_in_id));

        if ($order_display_only_assigned_account == '1') {
            $account_group_ids = $this->applib->current_user_account_group_ids();
            $account_ids = $this->applib->current_user_account_ids();
        }

        $this->db->select("a.account_id,a.account_name,a.account_mobile", $search, $page);
        $this->db->from("account a");

        if ($order_display_only_assigned_account == '1') {
            if (!empty($account_group_ids)) {
                $a_g_ids = array();
                if (in_array(CUSTOMER_GROUP, $account_group_ids)) {
                    $a_g_ids[] = CUSTOMER_GROUP;
                }
                if (in_array(SUNDRY_CREDITORS_ACCOUNT_GROUP, $account_group_ids)) {
                    $a_g_ids[] = SUNDRY_CREDITORS_ACCOUNT_GROUP;
                }
                if (in_array(SUNDRY_DEBTORS_ACCOUNT_GROUP, $account_group_ids)) {
                    $a_g_ids[] = SUNDRY_DEBTORS_ACCOUNT_GROUP;
                }
                $this->db->where_in('a.account_group_id', $a_g_ids);
            } else {
                $this->db->where_in('a.account_group_id', array(-1));
            }

            if ($account_ids == "allow_all_accounts") {
                
            } elseif (!empty($account_ids)) {
                $this->db->where_in('a.account_id', $account_ids);
            } else {
                $this->db->where_in('a.account_id', array(-1));
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("a.account_mobile", $search);
            $this->db->group_end();
        }

        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();

        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");


        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name . ' - ' . $row->account_mobile,
                );
            }
        }

        $this->db->select("a.account_id");
        $this->db->from("account a");

        if ($order_display_only_assigned_account == '1') {
            if (!empty($account_group_ids)) {
                $a_g_ids = array();
                if (in_array(CUSTOMER_GROUP, $account_group_ids)) {
                    $a_g_ids[] = CUSTOMER_GROUP;
                }
                if (in_array(SUNDRY_CREDITORS_ACCOUNT_GROUP, $account_group_ids)) {
                    $a_g_ids[] = SUNDRY_CREDITORS_ACCOUNT_GROUP;
                }
                if (in_array(SUNDRY_DEBTORS_ACCOUNT_GROUP, $account_group_ids)) {
                    $a_g_ids[] = SUNDRY_DEBTORS_ACCOUNT_GROUP;
                }
                $this->db->where_in('a.account_group_id', $a_g_ids);
            } else {
                $this->db->where_in('a.account_group_id', array(-1));
            }
            if ($account_ids == "allow_all_accounts") {
                
            } elseif (!empty($account_ids)) {
                $this->db->where_in('a.account_id', $account_ids);
            } else {
                $this->db->where_in('a.account_id', array(-1));
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("a.account_mobile", $search);
            $this->db->group_end();
        }
        $this->db->group_by('a.account_id');
        $query = $this->db->get();

        $results = array(
            "results" => $select2_data,
            "total_count" => $query->num_rows(),
        );
        echo json_encode($results);
        exit();
    }

    function party_name_with_number_select2_source($is_active = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        
        $account_group_ids = $this->applib->current_user_account_group_ids();
        $account_ids = $this->applib->current_user_account_ids();

        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        if(!empty($account_group_ids)){
            $a_g_ids = array();
            if(in_array(CUSTOMER_GROUP, $account_group_ids)){
                $a_g_ids[] = CUSTOMER_GROUP;
            }
            if(in_array(SUNDRY_CREDITORS_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = SUNDRY_CREDITORS_ACCOUNT_GROUP;
            }
            if(in_array(SUNDRY_DEBTORS_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = SUNDRY_DEBTORS_ACCOUNT_GROUP;
            }
            $a_g_ids[] = WORKER_GROUP;
            $this->db->where_in('a.account_group_id', $a_g_ids);
        } else {
            $this->db->where_in('a.account_group_id', array(-1));
        }
        if(!empty($is_active)){
            $this->db->where('a.is_active', '1');
        }
        if($account_ids == "allow_all_accounts") {
        } elseif(!empty($account_ids)) {
            $this->db->where_in('a.account_id', $account_ids);
        } else {
            $this->db->where_in('a.account_id', array(-1));
        }

        if(!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("a.account_mobile", $search);
            $this->db->group_end();    
        }

        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");		
        

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }

        $this->db->select("a.account_id");
        $this->db->from("account a");
        if(!empty($account_group_ids)){
            $a_g_ids = array();
            if(in_array(CUSTOMER_GROUP, $account_group_ids)){
                $a_g_ids[] = CUSTOMER_GROUP;
            }
            if(in_array(SUNDRY_CREDITORS_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = SUNDRY_CREDITORS_ACCOUNT_GROUP;
            }
            if(in_array(SUNDRY_DEBTORS_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = SUNDRY_DEBTORS_ACCOUNT_GROUP;
            }
            $this->db->where_in('a.account_group_id', $a_g_ids);
        } else {
            $this->db->where_in('a.account_group_id', array(-1));
        }
        if($account_ids == "allow_all_accounts") {
        } elseif(!empty($account_ids)) {
            $this->db->where_in('a.account_id', $account_ids);
        } else {
            $this->db->where_in('a.account_id', array(-1));
        }
        if(!empty($is_active)){
            $this->db->where('a.is_active', '1');
        }
        if(!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("a.account_mobile", $search);
            $this->db->group_end();    
        }
        $this->db->group_by('a.account_id');
        $query = $this->db->get();

        $results = array(
            "results" => $select2_data,
            "total_count" => $query->num_rows(),
        );
       echo json_encode($results);
        exit();
    }
    
    function supplier_name_with_number_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.is_supplier',1);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");        
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }

        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.is_supplier',1);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $select2_data,
            "total_count" => $total_count,
        );
        echo json_encode($results);
        exit();
    }
    
    function supplier_worker_with_number_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.is_supplier',1);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }
        
        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.is_supplier',1);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $query = $this->db->get();
        $total_count1 = $query->num_rows();
        
        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.account_group_id',WORKER_GROUP);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }
        
        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.is_supplier',1);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $query = $this->db->get();
        $total_count2 = $query->num_rows();
        
        $total_count = $total_count1 + $total_count2;
        $results = array(
            "results" => $select2_data,
            "total_count" => $total_count,
        );
        echo json_encode($results);
        exit();
    }
    
    function supplier_filter_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $select2_data[] = array(
            'id' => 'ALL',
            'text' => 'ALL',
        );
        $select2_data[] = array(
            'id' => 'OnlySupplier',
            'text' => 'Only Supplier Orders',
        );
        $select2_data[] = array(
            'id' => 'ExcludeSupplier',
            'text' => 'Exclude Supplier Orders',
        );

        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.is_supplier',1);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");        
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }

        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.is_supplier',1);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $select2_data,
            "total_count" => ($total_count + 3),
        );
        echo json_encode($results);
        exit();
    }
    
    function account_name_with_number_select2_source($account_group_id = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 15;
        $offset = ($page - 1) * $resultCount;

        $account_group_ids = $this->applib->current_user_account_group_ids();
        $account_ids = $this->applib->current_user_account_ids();

        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        if(!empty($account_group_id)){
            $this->db->where('a.account_group_id', $account_group_id);
        }
        
        if(!empty($account_group_ids)){
            $this->db->where_in('a.account_group_id', $account_group_ids);
        } else {
            $this->db->where_in('a.account_group_id', array(-1));
        }
        
        if($account_ids == "allow_all_accounts") {
            
        } elseif(!empty($account_ids)) {
            $this->db->where_in('a.account_id', $account_ids);
        } else {
            $this->db->where_in('a.account_id', array(-1));
        }
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");
        
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
       echo json_encode($results);
        exit();
    }
    
    function account_name_with_number_without_department_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.account_group_id !=', DEPARTMENT_GROUP);
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");		
        
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
       echo json_encode($results);
        exit();
    }
    
    function account_name_with_number_without_department_without_case_customer_select2_source($is_active = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $account_group_ids = $this->applib->current_user_account_group_ids();
        $account_ids = $this->applib->current_user_account_ids();

        $this->db->select("a.account_id,a.user_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        $this->db->where('a.account_group_id !=', DEPARTMENT_GROUP);
        $this->db->where('a.account_id !=', CASE_CUSTOMER_ACCOUNT_ID);
        
        if(!empty($account_group_ids)){
            $this->db->where_in('a.account_group_id', $account_group_ids);
        } else {
            $this->db->where_in('a.account_group_id', array(-1));
        }
        if($account_ids == "allow_all_accounts") {
            
        } elseif(!empty($account_ids)) {
            $this->db->where_in('a.account_id', $account_ids);
        } else {
            $this->db->where_in('a.account_id', array(-1));
        }
        if(!empty($is_active)){
            $this->db->where('a.is_active', '1');
        }
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");		
        
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
       echo json_encode($results);
        exit();
    }

    function set_account_name_with_number_val_by_id($id){
       $this->db->select("a.account_id,a.account_name,a.account_mobile");
       $this->db->from("account a");
       $this->db->where('a.account_id', $id);
       $this->db->limit(1);
       $query = $this->db->get();

       if ($query->num_rows() > 0) {
           foreach ($query->result() as $row) {
           echo json_encode(array(
               'success' => true,
               'id' => $row->account_id,
               'text' => $row->account_name .' - '.$row->account_mobile)
               );
           }
           exit();
       }
       echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
       exit();
   }
   
    function category_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$results = array(
			"results" => $this->get_select2_data('category', 'category_id', 'category_name', $search, $page),
			"total_count" => $this->count_select2_data('category', 'category_id', 'category_name', $search),
		);
		echo json_encode($results);
		exit();
    }
    
    function category_for_gold_and_silver_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('category_group_id !=' => CATEGORY_GROUP_OTHER_ID);
        $results = array(
                "results" => $this->get_select2_data('category', 'category_id', 'category_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('category', 'category_id', 'category_name', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function set_category_select2_val_by_id($id){
            $this->get_select2_text_by_id('category', 'category_id', 'category_name', $id);
    }
    
    function item_name_from_select_category_for_sell_select2_source($category_id,$sell_type_id){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if($sell_type_id == '1'){
            $sell_type = 'Sell';
        } elseif($sell_type_id == '2'){
            $sell_type = 'Purchase';
        } elseif ($sell_type_id == '3') {
            $sell_type = 'Exchange';
        }

        if($sell_type_id == '1' || $sell_type_id == '2' || $sell_type_id == '3'){
            $where = "CONCAT(',', display_item_in, ',') like '%".$sell_type."%'";
            if(!empty($category_id)){
                $where .= " AND category_id =".$category_id;
            } else {
                $category_arr = $this->crud->getFromSQL('SELECT `category_id` FROM `category` WHERE `category_group_id` = "' . CATEGORY_GROUP_OTHER_ID . '"');
                foreach ($category_arr as $category){
                    $category_ids[] = $category->category_id;
                    $where .= " AND category_id !=".$category->category_id;
                }
            }
        }else{
            $where = array('metal_payment_receipt' => '1', 'stock_method' => '3');
        }

        $results = array(
                "results" => $this->get_select2_data('item_master', 'item_id', 'item_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search,$where),
        );
        echo json_encode($results);
        exit();
    }
    
    function item_name_from_select_category_for_metal_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('metal_payment_receipt' => '1', 'stock_method' => '3');
        $results = array(
                "results" => $this->get_select2_data('item_master', 'item_id', 'item_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search,$where),
        );
//        echo $this->db->last_query();
        echo json_encode($results);
        exit();
    }
    
    function item_default_and_combine_for_metal_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('metal_payment_receipt' => '1', 'stock_method !=' => STOCK_METHOD_ITEM_WISE);
        $results = array(
                "results" => $this->get_select2_data('item_master', 'item_id', 'item_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search,$where),
        );
//        echo $this->db->last_query();
        echo json_encode($results);
        exit();
    }

    function item_name_from_category_select2_source($category_id = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array();
        if(!empty($category_id)){
            $where['category_id'] = $category_id;
        }
        $results = array(
                "results" => $this->get_select2_data('item_master', 'item_id', 'item_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search,$where),
        );
        echo json_encode($results);
        exit();
    }

    function item_name_defult_from_category_select2_source($category_id){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('category_id' => $category_id);
        $results = array(
                "results" => $this->get_select2_data('item_master', 'item_id', 'item_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search,$where),
        );
        echo json_encode($results);
        exit();
    }
        
    function category_group_select2_source(){
            $search = isset($_GET['q']) ? $_GET['q'] : '';
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $results = array(
                    "results" => $this->get_select2_data('category_group', 'category_group_id', 'category_group_name', $search, $page),
                    "total_count" => $this->count_select2_data('category_group', 'category_group_id', 'category_group_name', $search),
            );
            echo json_encode($results);
            exit();
    }
    
    function set_category_group_select2_val_by_id($id){
            $this->get_select2_text_by_id('category_group', 'category_group_id', 'category_group_name', $id);
    }
    
    function account_group_select2_source_for_account(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = 'account_group_id NOT IN ('.ADMIN_GROUP.','.USER_GROUP.','.WORKER_GROUP.','.SALESMAN_GROUP.')';
        $account_group_ids = $this->applib->current_user_account_group_ids();
        if(!empty($account_group_ids)){
            $where .= ' AND account_group_id IN('.implode(',',$account_group_ids).')';
        } else {
            $where .= ' AND account_group_id IN(-1)';
        }
        $results = array(
                "results" => $this->get_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $where),
        );
        echo json_encode($results);
        exit();
    }
    
    function set_account_group_select2_val_by_id($id){
        $this->get_select2_text_by_id('account_group', 'account_group_id', 'account_group_name', $id);
    }
        
    function account_group_select2_source(){
            $search = isset($_GET['q']) ? $_GET['q'] : '';
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $where ='parent_group_id NOT IN ('.ADMIN_GROUP.','.USER_GROUP.','.WORKER_GROUP.','.SALESMAN_GROUP.')';
            $results = array(
                    "results" => $this->get_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $page, $where),
                    "total_count" => $this->count_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $where),
            );
//		echo '<pre>';print_r($results);exit;
            echo json_encode($results);
            exit();
    }
    
    function state_select2_source(){
            $search = isset($_GET['q']) ? $_GET['q'] : '';
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $where = '';
            $results = array(
                    "results" => $this->get_select2_data('state', 'state_id', 'state_name', $search, $page, $where),
                    "total_count" => $this->count_select2_data('state', 'state_id', 'state_name', $search, $where),
            );
            echo json_encode($results);
            exit();
    }
    
    function set_state_select2_val_by_id($id){
        $this->get_select2_text_by_id('state', 'state_id', 'state_name', $id);
    }

    function city_select2_source($state_id){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('city', 'city_id', 'city_name', $search, $page, array('state_id' => $state_id)),
			"total_count" => $this->count_select2_data('city', 'city_id', 'city_name', $search, array('state_id' => $state_id)),
		);
		echo json_encode($results);
		exit();
	}
        
    function set_city_select2_val_by_id($id){
            $this->get_select2_text_by_id('city', 'city_id', 'city_name', $id);
    }

    function sell_type_select2_source($sell_purchase = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = '';

        $result_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("sell_type_id,type_name");
        $this->db->from("sell_type");
        if($sell_purchase == "sell") {
            $this->db->where_in("sell_type_id",array(SELL_TYPE_SELL_ID));
        }
        if($sell_purchase == "purchase") {
            $this->db->where_in("sell_type_id",array(SELL_TYPE_PURCHASE_ID,SELL_TYPE_EXCHANGE_ID));
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->like("type_name", $search, 'after');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("type_name");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $string = $row->type_name;
                $result_data[] = array(
                        'id' => $row->sell_type_id,
                        'text' => $string[0],
                );
            }
        }

        $this->db->select("sell_type_id,type_name");
        $this->db->from("sell_type");
        if($sell_purchase == "sell") {
            $this->db->where_in("sell_type_id",array(SELL_TYPE_SELL_ID));
        }
        if($sell_purchase == "purchase") {
            $this->db->where_in("sell_type_id",array(SELL_TYPE_PURCHASE_ID,SELL_TYPE_EXCHANGE_ID));
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->like("type_name",$search);
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("type_name");
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
                "results" => $result_data,
                "total_count" => $total_count,
        );
        echo json_encode($results);
        exit();
    }
    
    function set_sell_type_select2_val_by_id($id){
        $this->get_select2_first_letter_text_by_id('sell_type', 'sell_type_id', 'type_name', $id);
	}
    
    function account_name_without_cash_customer_select2_source($department_id = '', $is_active = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 30;
        $offset = ($page - 1) * $resultCount;
        $account_group_ids = $this->applib->current_user_account_group_ids();
        $account_ids = $this->applib->current_user_account_ids();

        $this->db->select("a.account_id,a.user_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        if(!empty($account_group_ids)){
            $this->db->where_in('a.account_group_id', $account_group_ids);
        } else {
            $this->db->where_in('a.account_group_id', array(-1));
        }
        if($account_ids == "allow_all_accounts") {
            
        } elseif(!empty($account_ids)) {
            $this->db->where_in('a.account_id', $account_ids);
        } else {
            $this->db->where_in('a.account_id', array(-1));
        }
        $this->db->where('a.account_id !=', '1');
        if(!empty($is_active)){
            $this->db->where('a.is_active', '1');
        }
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $user_status = 0;
                if($row->user_id != null){
                    $user_status = $this->crud->get_column_value_by_id('user_master','status',array('user_id' => $row->user_id));
                }
                if($user_status == 0) {
                    $select2_data[] = array(
                       'id' => $row->account_id,
                        'text' => $row->account_name .' - ' .$row->account_mobile,
                    );
                }
            }
        }
        $results = array(
                "results" => $select2_data,
                "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
//            echo '<pre>'.$this->db->last_query(); exit;
        echo json_encode($results);
        exit();
    }

    function account_name_from_main_party_select2_source($account_id = '', $is_active = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $account_group_ids = $this->applib->current_user_account_group_ids();
        $account_ids = $this->applib->current_user_account_ids();

        $this->db->select("a.account_id,a.account_name,a.account_mobile",$search,$page);
        $this->db->from("account a");
        
        if(!empty($account_group_ids)){
            $a_g_ids = array();
            if(in_array(CUSTOMER_GROUP, $account_group_ids)){
                $a_g_ids[] = CUSTOMER_GROUP;
            }
            if(in_array(SUNDRY_CREDITORS_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = SUNDRY_CREDITORS_ACCOUNT_GROUP;
            }
            if(in_array(SUNDRY_DEBTORS_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = SUNDRY_DEBTORS_ACCOUNT_GROUP;
            }
            if(in_array(EXPENSE_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = EXPENSE_ACCOUNT_GROUP;
            }
            if(in_array(INCOME_OTHER_THEN_SALES_ACCOUNT_GROUP, $account_group_ids)){
                $a_g_ids[] = INCOME_OTHER_THEN_SALES_ACCOUNT_GROUP;
            }
            $this->db->where_in('a.account_group_id', $a_g_ids);
        } else {
            $default_account_group_ids = array(-1);
            $this->db->where_in('a.account_group_id', $default_account_group_ids);
        }
        
        if($account_ids == "allow_all_accounts") {
            
        } elseif(!empty($account_ids)) {
            $this->db->where_in('a.account_id', $account_ids);
        } else {
            $this->db->where_in('a.account_id', array(-1));
        }
        $this->db->where('account_id !=', $account_id);
        if(!empty($is_active)){
            $this->db->where('a.is_active', '1');
        }
        $this->db->group_start();
        $this->db->where('account_id !=', CASE_CUSTOMER_ACCOUNT_ID);
        $this->db->or_where('account_id', CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID);//For Customer Monthly Interest Account
        $this->db->or_where('account_id', ADJUST_EXPENSE_ACCOUNT_ID); // For Adjust Account
        $this->db->group_end();
        $this->db->group_start();
        $this->db->or_like("a.account_name", $search);
        $this->db->or_like("a.account_mobile", $search);
        $this->db->group_end();
        $this->db->group_by('a.account_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");
        
        $query = $this->db->get();
//        echo '<pre>'.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->account_name .' - ' .$row->account_mobile,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function set_account_name_select2_val_by_id($id){
            $this->get_select2_text_by_id('account', 'account_id', 'account_name', $id);
	}
    
//    function account_bank_select2_source(){
//        $search = isset($_GET['q']) ? $_GET['q'] : '';
//        $page = isset($_GET['page']) ? $_GET['page'] : 1;
//        $where = array('account_group_id'=> BANK_ACCOUNT_GROUP);
//        $results = array(
//                "results" => $this->get_select2_data('account', 'bank_name', 'bank_name', $search, $page, $where),
//                "total_count" => $this->count_select2_data('account', 'bank_name', 'bank_name', $search),
//        );
//        echo json_encode($results);
//        exit();
//	}
    
    function account_bank_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("account_id,bank_name,bank_account_no",$search,$page);
        $this->db->from("account");
        $this->db->where('account_group_id', BANK_ACCOUNT_GROUP);
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("account_id");		
        $this->db->like("bank_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                    'text' => $row->bank_name .' - ' .$row->bank_account_no,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'bank_name', $search),
        );
       echo json_encode($results);
        exit();
    }
    
    function set_account_bank_select2_val_by_id($id){
       $this->db->select("account_id,bank_name,bank_account_no");
       $this->db->from("account a");
       $this->db->where('a.account_id', $id);
       $this->db->limit(1);
       $query = $this->db->get();

       if ($query->num_rows() > 0) {
           foreach ($query->result() as $row) {
           echo json_encode(array(
               'success' => true,
               'id' => $row->account_id,
               'text' => $row->bank_name .' - '.$row->bank_account_no)
               );
           }
           exit();
       }
       echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
       exit();
   }
    
    function category_from_stock_department_select2_source($from_department=''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->distinct();
        $this->db->select("c.category_id, c.category_name",$search,$page);
        $this->db->from("category c");
        $this->db->join('item_stock i', 'i.category_id = c.category_id');
        $this->db->where('i.department_id', $from_department);
        $this->db->where('i.grwt !=', '0');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("c.category_id");
        $this->db->like("c.category_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->category_id,
                    'text' => $row->category_name,
                );
            }
        }
//        echo $this->db->last_query();
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('category', 'category_id', 'category_name', $search),
        );
        echo json_encode($results);
        exit();
	}
    
    function item_from_stock_category_select2_source($category_id=''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->distinct();
        $this->db->select("im.item_id, im.item_name",$search,$page);
        $this->db->from("item_master im");
        $this->db->join('item_stock i', 'i.item_id = im.item_id');
        if(!empty($category_id)){
            $this->db->where('i.category_id', $category_id);
        }
        $this->db->where('i.grwt !=', '0');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("im.item_id");
        $this->db->like("im.item_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->item_id,
                    'text' => $row->item_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search),
        );
        echo json_encode($results);
        exit();
	}
    
    function tunch_from_stock_item_select2_source($item_id='', $from_department='', $category_id=''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('grwt !=' => 0);
        if(!empty($item_id)){
            $where['item_id'] = $item_id;
        }
        if(!empty($from_department)){
            $where['department_id'] = $from_department;
        }
        if(!empty($category_id)){
            $where['category_id'] = $category_id;
        }
        $results = array(
                "results" => $this->get_select2_data('item_stock', 'tunch', 'tunch', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_stock', 'tunch', 'tunch', $search),
        );
        echo json_encode($results);
        exit();
	}
        
    function set_tunch_from_stock_select2_val_by_id($id){
        $tnch = $this->get_select2_text_by_id('carat', 'purity', 'purity', $id);
    }
    
    function order_status_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = '';
        $results = array(
                "results" => $this->get_select2_data('order_status', 'order_status_id', 'status', $search, $page),
                "total_count" => $this->count_select2_data('order_status', 'order_status_id', 'status', $search),
        );
        echo json_encode($results);
        exit();
	}
    
    function set_order_status_select2_val_by_id($id){
        $this->get_select2_text_by_id('order_status', 'order_status_id', 'status', $id);
    }
    
    function user_master_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('status' => 0);
        $results = array(
                "results" => $this->get_select2_data('user_master', 'user_id', 'user_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('user_master', 'user_id', 'user_name', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function set_user_master_select2_val_by_id($id){
        $this->get_select2_text_by_id('user_master', 'user_id', 'user_name', $id);
    }
    
    function item_name_for_order_select2_source($category_id=''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $sell_type = 'Sell';
        $where = "CONCAT(',', display_item_in, ',') like '%".$sell_type."%' and category_id =".$category_id;
        $results = array(
                "results" => $this->get_select2_data('item_master', 'item_id', 'item_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search,$where),
        );
        echo json_encode($results);
        exit();
    }
    
    function user_type_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = '';
        $results = array(
                "results" => $this->get_select2_data('user_type', 'user_type_id', 'user_type', $search, $page),
                "total_count" => $this->count_select2_data('user_type', 'user_type_id', 'user_type', $search),
        );
        echo json_encode($results);
        exit();
	}
    
    function set_user_type_select2_val_by_id($id){
        $this->get_select2_text_by_id('user_type', 'user_type_id', 'user_type', $id);
    }

    function user_worker_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.user_id, a.account_name");
        $this->db->from("account a");
        $this->db->join('user_master u', 'u.user_id = a.user_id');
        $this->db->where_in('a.account_group_id', array(USER_GROUP,ADMIN_GROUP,WORKER_GROUP,SALESMAN_GROUP));
        $this->db->where('u.status', '0');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->user_id,
                   'text' => $row->account_name,
                );
            }
        }
        $results = array(
                "results" => $select2_data,
                "total_count" => count($select2_data),
        );
        echo json_encode($results);
        exit();
	}

    function active_accounts_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id,a.account_name");
        $this->db->from("account a");
        $this->db->join("user_master u",'u.user_id = a.user_id');
        $this->db->limit($resultCount, $offset);
        $this->db->where('u.status', '0');
        $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("a.account_id", $search);
        $this->db->group_end();
        $this->db->order_by("a.account_id");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                   'text' => $row->account_id.' - '.$row->account_name,
                );
            }
        }
        $results = array(
                "results" => $select2_data,
                "total_count" => $this->accounts_count_select2_data($search),
        );
        echo json_encode($results);
        exit();
    }

    function accounts_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id,a.account_name");
        $this->db->from("account a");
        $this->db->join("user_master u",'u.user_id = a.user_id');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $this->db->or_like("a.account_id", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                   'text' => $row->account_id.' - '.$row->account_name,
                );
            }
        }
        $results = array(
                "results" => $select2_data,
                "total_count" => $this->accounts_count_select2_data($search),
        );
        echo json_encode($results);
        exit();
    }

    function accounts_count_select2_data($search){
        $this->db->select("a.account_id,a.account_name");
        $this->db->from("account a");
        $this->db->join("user_master u",'u.user_id = a.user_id');
        $this->db->order_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $this->db->or_like("a.account_id", $search);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function set_user_worker_select2_val_by_id($id){
        $this->get_select2_text_by_id('account', 'user_id', 'account_name', $id);
    }

    function worker_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id, a.user_id, a.account_name");
        $this->db->from("account a");
        $this->db->join('user_master u', 'u.user_id = a.user_id');
        $this->db->where('a.account_group_id', WORKER_GROUP);
        $this->db->where('u.status', '0');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                   'text' => $row->account_name,
                );
            }
        }
        $results = array(
                "results" => $select2_data,
                "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
    }

    function worker_supplier_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id, a.user_id, a.account_name");
        $this->db->from("account a");
        $this->db->join('user_master u', 'u.user_id = a.user_id');
        $this->db->where('a.account_group_id', WORKER_GROUP);
        $this->db->where('u.status', '0');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                   'text' => $row->account_name,
                );
            }
        }
        
        $this->db->select("a.account_id, a.account_name");
        $this->db->from("account a");
        $this->db->where('a.is_supplier', '1');
        $this->db->order_by("a.account_name");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name,
                );
            }
        }
        
        $results = array(
                "results" => $select2_data,
                "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
    }
    
    function cad_worker_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id, a.user_id, a.account_name");
        $this->db->from("account a");
        $this->db->join('user_master u', 'u.user_id = a.user_id');
        $this->db->where('a.account_group_id', WORKER_GROUP);
        $this->db->where('u.is_cad_designer', '1');
        $this->db->where('u.status', '0');
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                   'text' => $row->account_name,
                );
            }
        }
        $results = array(
                "results" => $select2_data,
                "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
    }
    
    function worker_from_department_select2_source(){
        $dep_id = isset($_POST['dep_id']) ? $_POST['dep_id'] : '';
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id, a.user_id, a.account_name");
        $this->db->from("account a");
        $this->db->join('user_master u', 'u.user_id = a.user_id');
        $this->db->join('user_department ud', 'ud.user_id = a.user_id');
        $this->db->where('a.account_group_id', WORKER_GROUP);
        $this->db->where('u.status', '0');
        $this->db->where_in('ud.department_id', $dep_id);
        $this->db->order_by("a.account_id");
        $this->db->group_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                   'id' => $row->account_id,
                   'text' => $row->account_name,
                );
            }
        }
        $results = array(
                "results" => $select2_data,
                "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
        
    }

    function set_worker_select2_val_by_id($id){
        $this->get_select2_text_by_id('account', 'account_id', 'account_name', $id);
    }
    
    function workers_department_select2_source($worker_id){
        $user_department_ids = $this->applib->current_user_department_ids();
        $worder_department_ids = $this->applib->current_worker_department_ids($worker_id);
        $department_ids = array_intersect($user_department_ids, $worder_department_ids);
		$search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        if(!empty($department_ids)){
            $this->db->select("a.account_id,a.account_name",$search,$page);
            $this->db->from("account a");
            $this->db->where('a.account_group_id', DEPARTMENT_GROUP);
            $this->db->where_in('account_id', $department_ids);
            $this->db->like("a.account_name", $search);
            $this->db->group_by('a.account_id');
            $this->db->limit($resultCount, $offset);
            $this->db->order_by("a.account_id");

            $query = $this->db->get();
//            echo '<pre>'. $this->db->last_query(); exit;
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $select2_data[] = array(
                       'id' => $row->account_id,
                        'text' => $row->account_name,
                    );
                }
            }
        }
        
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
        echo json_encode($results);
        exit();
	}
    
    function category_for_other_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('category_group_id' => CATEGORY_GROUP_OTHER_ID);
        $results = array(
                "results" => $this->get_select2_data('category', 'category_id', 'category_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('category', 'category_id', 'category_name', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function other_type_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('sell_type_id !=' => '3');
        $results = array(
                "results" => $this->get_select2_data_first_letter('sell_type', 'sell_type_id', 'type_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('sell_type', 'sell_type_id', 'type_name', $search),
        );
        echo json_encode($results);
        exit();
    }
    
    function item_name_from_category_with_all_select2_source($category_id){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('category_id' => $category_id);
        $results = array(
                "results" => $this->get_select2_data('item_master', 'item_id', 'item_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('item_master', 'item_id', 'item_name', $search,$where),
        );
        array_unshift($results['results'], array('id' => 'all', 'text' => 'All'));
//        echo "<pre>"; print_r($results); exit;
        echo json_encode($results);
        exit();
    }
    
    function operation_from_department_select2_source($dp_id = '') {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("o.operation_id, o.operation_name");
        $this->db->from("operation o");
        if(!empty($dp_id)){
            $this->db->join('operation_department od', 'od.operation_id = o.operation_id');
            $this->db->where('od.department_id', $dp_id);
        }
        $this->db->order_by("o.operation_id");
        $this->db->group_by("o.operation_id");
        $this->db->like("o.operation_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->operation_id,
                    'text' => $row->operation_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
    }
    
    function set_operation_select2_val_by_id($id){
        $this->get_select2_text_by_id('operation', 'operation_id', 'operation_name', $id);
    }

    function worker_from_operation_select2_source($operation_id = '') {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id, a.account_name");
        $this->db->from("operation_worker ow");
        $this->db->join('account a', 'a.account_id = ow.worker_id');
        $this->db->where('ow.operation_id', $operation_id);
        $this->db->order_by("a.account_id");
        $this->db->group_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name,
                );
            }
        }
        
        $results = array(
            "results" => $select2_data,
            "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
    }

    function machine_chain_operation_from_department_select2_source($dp_id = '') {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("mco.operation_id, mco.operation_name");
        $this->db->from("machine_chain_operation mco");
        if(!empty($dp_id)){
            $this->db->join('machine_chain_operation_department mcod', 'mcod.operation_id = mco.operation_id');
            $this->db->where('mcod.department_id', $dp_id);
        }
        $this->db->order_by("mco.sequence_no");
        $this->db->group_by("mco.operation_id");
        $this->db->like("mco.operation_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->operation_id,
                    'text' => $row->operation_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
    }
    
    function set_machine_chain_operation_select2_val_by_id($id){
        $this->get_select2_text_by_id('machine_chain_operation', 'operation_id', 'operation_name', $id);
    }

    function worker_from_machine_chain_operation_select2_source($operation_id = '') {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;

        $this->db->select("a.account_id, a.account_name");
        $this->db->from("machine_chain_operation_worker mcow");
        $this->db->join('account a', 'a.account_id = mcow.worker_id');
        $this->db->where('mcow.operation_id', $operation_id);
        $this->db->order_by("a.account_id");
        $this->db->group_by("a.account_id");
        $this->db->like("a.account_name", $search);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => count($select2_data),
        );

        echo json_encode($results);
        exit();
    }
    
    function get_curb_box_default_value() 
    {
        $post_data = $this->input->post();
        $curb_box = $post_data['curb_box'];

        $default_value = 0;

        if($curb_box == MCO_SOLDING_CURB_ID) {
            $default_value = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'machine_chain_operation_solding_curb_default_value'));
        } elseif ($curb_box == MCO_SOLDING_BOX_ID) {
            $default_value = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'machine_chain_operation_solding_box_default_value'));
        }
        echo json_encode(array('default_value' => $default_value));
    }

    function get_account_detail($account_id)
    {
        $res = array();
        $this->db->select("a.*");
        $this->db->from('account a');
        $this->db->where('a.account_id', $account_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $res['success'] = 'true';
            $account_data = $query->row_array(0);
            if(empty($account_data['account_state']) || $account_data['account_state'] == HOME_STATE_ID) {
                $account_data['cgst_per'] = CGST_PER;
                $account_data['sgst_per'] = SGST_PER;
                $account_data['igst_per'] = '';
            } else {
                $account_data['cgst_per'] = '';
                $account_data['sgst_per'] = '';
                $account_data['igst_per'] = IGST_PER;
            }
            if(empty($account_data['price_per_pcs'])){
                $account_data['price_per_pcs'] = $this->crud->get_val_by_id('settings', 'price_per_pcs', 'settings_key', 'settings_value');
            }
            $res['account_data'] = $account_data;
        } else {
            $res['success'] = 'false';
        }
        echo json_encode($res);
    }
    
    function manufacture_status_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("manufacture_status_name, manufacture_status_id");
        $this->db->from("manufacture_status");
        $this->db->order_by("manufacture_status_id");
        $this->db->like("manufacture_status_name", $search);
        $this->db->limit($resultCount, $offset);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->manufacture_status_id,
                    'text' => $row->manufacture_status_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => count($select2_data),
        );
        echo json_encode($results);
        exit();
    }
    
    function set_manufacture_status_select2_val_by_id($id){
        $this->get_select2_text_by_id('manufacture_status', 'manufacture_status_id', 'manufacture_status_name', $id);
    }
    
    function ad_name_select2_source($ad_for = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("ad_name, ad_id");
        $this->db->from("ad");
        if(!empty($ad_for)){
            $this->db->where($ad_for, '1');
        }
        $this->db->order_by("ad_id");
        $this->db->like("ad_name", $search);
        $this->db->limit($resultCount, $offset);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->ad_id,
                    'text' => $row->ad_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => count($select2_data),
        );
        echo json_encode($results);
        exit();
    }
    
    function set_ad_name_select2_val_by_id($id){
        $this->get_select2_text_by_id('ad', 'ad_id', 'ad_name', $id);
    }

    function stamp_select2_source() {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = '';
        $results = array(
            "results" => $this->get_select2_data('stamp', 'stamp_id', 'stamp_name', $search, $page),
            "total_count" => $this->count_select2_data('stamp', 'stamp_id', 'stamp_name', $search),
        );
        echo json_encode($results);
        exit();
    }

    function set_stamp_select2_val_by_id($id) {
        $this->get_select2_text_by_id('stamp', 'stamp_id', 'stamp_name', $id);
    }

}
 
