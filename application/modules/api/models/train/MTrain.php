<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over token 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/api/token
 * @category        common to all
 * @author          Barun Pandey
 * @date            13 Oct, 2020, 12:46:00 PM
 * @version         1.0.0
 */
class mTrain extends CI_Model {

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
             $this->db->order_by('STA', 'DESC');
            $data = $this->db->get_where("train_list", ['trainNo' => $id])->row_array();
        } else {
            $this->db->order_by('trainNo', 'DESC');
            // $this->db->limit(4,$limit);
            $data = $this->db->get("train_list")->result();
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
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function insertData($input) {
        $data = $this->db->insert('train_list', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('train_list', array('trainNo' => $id));
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
        $data = $this->db->update('train_list', $input, array('trainNo' => $id));
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
        $data = $this->db->delete('train_list', array('trainNo' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
