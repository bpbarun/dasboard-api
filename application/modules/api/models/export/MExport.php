<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over Export 
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/export
 * @category        common to all
 * @author          Barun Pandey
 * @date            24 August, 2019, 01:19:00 PM
 * @version         1.0.0
 */
class mExport extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => '', 'response_tag' => 220);
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getData($id) {
        if (!empty($id)) {
            $data = $this->db->get_where("countobject", ['id' => $id])->row_array();
        } else {
            $this->db->order_by('object', 'DESC');
//            $this->db->limit(1);
            $data = $this->db->get("countobject")->result_array();
//            print_r($data); die;
        }
        if (!empty($data)) {
//            $response['status'] = TRUE;
            $response['draw'] = 1;
            $response['recordsTotal'] = COUNT($data);
            $response['recordsFiltered'] = COUNT($data);
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
        $data = $this->db->insert('countobject', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('countobject', array('id' => $id));
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
        $data = $this->db->update('countobject', $input, array('id' => $id));
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
        $data = $this->db->delete('countobject', array('id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
