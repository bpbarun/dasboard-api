<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over token 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/api/token
 * @category        common to all
 * @author          Barun Pandey
 * @date            17 June, 2020, 07:12:00 PM
 * @version         1.0.0
 */
class mEvent extends CI_Model {

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
    public function getData($id) {
        if (!empty($id)) {
              $data = $this->db->get_where("event", ['event_id' => $id])->row_array();
        } else {
            $this->db->order_by('event_id', 'DESC');
            $data = $this->db->get("event")->result();
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        
        return $response;
    }

    public function getCurrentData($id){
         if (!empty($id)) {
              $data = $this->db->get_where("event", ['event_id' => $id,'event_date' >= date("Y-m-d")])->row_array();
        } else {
            $currentDate = date('Y-m-d');  
            $this->db->select('*');
            $this->db->from('event');
            $this->db->where('event_date >=',$currentDate);
           
            $data = $this->db->get()->result_array();


            // $this->db->order_by('event_id', 'DESC');
            // $data = $this->db->get("event")->where()->result();
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        // echo $this->db->last_query();
        
        return $response;
    }

     public function getPastData($id){
         if (!empty($id)) {
              $data = $this->db->get_where("event", ['event_id' => $id,'event_date' >= date("Y-m-d")])->row_array();
        } else {
            $currentDate = date('Y-m-d');  
            $this->db->select('*');
            $this->db->from('event');
            $this->db->where('event_date <',$currentDate);
           $data = $this->db->get()->result_array();
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        // echo $this->db->last_query();
        
        return $response;
    }
    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function insertData($input) {
        $data = $this->db->insert('event', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('event', array('event_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Update Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input) {
        $data = $this->db->update('event', $input, array('event_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['error'] = 'No recerd updated in database';
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id) {
        $data = $this->db->delete('event', array('event_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
