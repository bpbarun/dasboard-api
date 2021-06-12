<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over feedback 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/api/feedback-amanora
 * @category        common to all
 * @author          Barun Pandey
 * @date            30 May, 2020, 07:27:00 PM
 * @version         1.0.0
 */
class mFeedbackQuestion extends CI_Model {

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
        try{
             if (!empty($id)) {
              $data = $this->db->get_where("amanora_feedback_question", ['feedback_question_id' => $id])->row_array();
        } else {
            $this->db->order_by('feedback_question_id', 'ASC');
            $data = $this->db->get("amanora_feedback_question")->result();
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        
        return $response;
        
        }catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
       
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function insertData($input) {
        try{
            $data = $this->db->insert('amanora_feedback_question', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('amanora_feedback_question', array('feedback_question_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
        }catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
    }

    /**
     * Update Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input) {
        try{
          $data = $this->db->update('event', $input, array('feedback_question_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['error'] = 'No recerd updated in database';
        }
        return $response;
        }catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id) {
        try{
            $data = $this->db->delete('amanora_feedback_question', array('feedback_question_id' => $id));
            if ($this->db->affected_rows() > 0) { // need to find no. of record affected
                $response['status'] = TRUE;
                $response['data'] = 'Record deleted successfully';
            } else {
                $response['error'] = 'Getting error please try after some time';
            }
            return $response;
      }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

}
