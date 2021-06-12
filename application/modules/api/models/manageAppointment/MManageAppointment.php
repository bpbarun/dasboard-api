<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

/**
 * This class is used for Crud operation over token 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/api/Token ManageAppointMent
 * @category        common to all
 * @author          Barun Pandey
 * @date            6th August, 2020, 06:51:00 PM
 * @version         1.0.0
 */
class mManageAppointment extends CI_Model {

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
              $data = $this->db->get_where("token_manege_appointment", ['manage_id' => $id])->row_array();
        } else {
            $this->db->order_by('manage_id', 'DESC');
            $data = $this->db->get("token_manege_appointment")->result();
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
        $data = $this->db->insert('token_manege_appointment', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('token_manege_appointment', array('manage_id' => $id));
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
        $data = $this->db->update('token_manege_appointment', $input, array('manage_id' => $id));
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
        $data = $this->db->delete('token_manege_appointment', array('manage_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
