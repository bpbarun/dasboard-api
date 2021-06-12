<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/album
 * @category        common to all
 * @author          Barun Pandey
 * @date            25 September, 2019, 05:56:00 PM
 * @version         1.0.0
 */
class mMultilingualConfig extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function selectR($input) {
        if (!empty($input['select'])) {
            $select = $input['select'];
        }
        if (!empty($input['where'])) {
            $whereCondition = $input['where'];
        }
        if (!empty($input['table'])) {
            $table = $input['table'];
        }
        if (!empty($input['created_by'])) {
            $createdBy = $input['created_by'];
        }
        $this->db->select($select);
        if (!empty($input['where'])) {
            $this->db->where($whereCondition);
        }
        if (!empty($createdBy)) {
            $this->db->where('created_by', $createdBy);
        }
        $data = $this->db->get($table)->result_array();
//        print_r($data); die;
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        return $response;
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getData($data) {
        $id = (!empty($data['id'])) ? $data['id'] : '';
        $userID = (!empty($data['created_by'])) ? $data['created_by'] : '';
        if (!empty($id)) {
            $this->db->where('created_by', $userID);
            $data = $this->db->get_where("multilingual_config", ['multilingual_config_id' => $id])->row_array();
        } else {
            $this->db->order_by('multilingual_config_id', 'ASC');
            $this->db->where('created_by', $userID);
            $data = $this->db->get("multilingual_config")->result();
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        return $response;
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function insertData($input) {
        $data = $this->db->insert('multilingual_config', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('multilingual_config', array('multilingual_config_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input) {
        $data = $this->db->update('multilingual_config', $input, array('multilingual_config_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id) {
        $data = $this->db->delete('multilingual_config', array('multilingual_config_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
