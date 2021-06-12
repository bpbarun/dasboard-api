<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over cort 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/api/cort
 * @category        common to all
 * @author          Barun Pandey
 * @date            26 March, 2021, 03:42:00 PM
 * @version         1.0.0
 */
class mCourt extends CI_Model {

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
            $data = $this->db->get_where("court", ['court_id' => $id])->row_array();
        } else {
            $this->db->order_by('court_id', 'ASC');
            $data = $this->db->get("court")->result();
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['status'] = FALSE;
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
        $data = $this->db->insert('court', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('court', array('court_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['status'] = FALSE;
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
        $data = $this->db->update('court', $input, array('court_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['status'] = FALSE;
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
        $data = $this->db->delete('court', array('court_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}