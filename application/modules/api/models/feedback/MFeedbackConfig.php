<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/album
 * @category        common to all
 * @author          Barun Pandey
 * @date            18 September, 2019, 03:03:00 PM
 * @version         1.0.0
 */
class mFeedbackConfig extends CI_Model {

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
    public function getData($data) {
        $id = (!empty($data['id'])) ? $data['id'] : '';
        $userID = (!empty($data['created_by'])) ? $data['created_by'] : '';
        if (!empty($id)) {
            $this->db->where('created_by', $userID);
            $this->db->where('config_type', 'image');

            $data = $this->db->get_where("feedback_config")->row_array();
        } else {
            $this->db->order_by('feedback_config_id', 'ASC');
            $this->db->where('created_by', $userID);
            $this->db->where('config_type', 'image');
            $data = $this->db->get("feedback_config")->result();
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
        $data = $this->db->insert('feedback_config', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('feedback_config', array('feedback_config_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function headerData($data) {
        $userID = (!empty($data['created_by'])) ? $data['created_by'] : '';
        $this->db->select('fs.string_name,fs.string_key_id,fs.feedback_string_id,fc.config_type');
        $this->db->join('feedback_string fs', 'fs.string_key_id = fc.config_value', 'right');
        $this->db->where('fs.lang', 'en');
        $this->db->where('fc.created_by', $userID);
        $data = $this->db->get("feedback_config fc")->result();
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
    public function headerDataUpdate($data) {

        $userID = (!empty($data['created_by'])) ? $data['created_by'] : '';
        unset($data['created_by']);
        $keyData = array_keys($data);
        $this->db->select("config_value,config_type");
        $this->db->where_in('config_type', $keyData);
        $configData = $this->db->get("feedback_config")->result();
        foreach ($data as $inputKey => $inputValue) {
            foreach ($configData as $fetchKey => $fetchValue) {
                if ($fetchValue->config_type == $inputKey) {
                    $finalSetData[] = array($inputValue => $fetchValue->config_value);
                }
            }
        }
        $success = 0;
        $updateData = array();
        foreach ($finalSetData as $keys => $Datavalue) {
            foreach ($Datavalue as $key => $value)
                $this->db->update('feedback_string', array('string_name' =>
                    $key), array('string_key_id' => $value, 'lang' => 'en'));
            if ($this->db->affected_rows() > 0) {
                $success++;
            }
        }
        if ($success > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
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
        $data = $this->db->update('feedback_config', $input, array('feedback_config_id' => $id));
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
        $data = $this->db->delete('feedback_config', array('feedback_config_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
