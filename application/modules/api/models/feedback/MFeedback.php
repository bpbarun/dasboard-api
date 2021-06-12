<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/album
 * @category        common to all
 * @author          Barun Pandey
 * @date            27 July, 2019, 02:22:00 PM
 * @version         1.0.0
 */
class mFeedback extends CI_Model {

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
            $data = $this->db->get_where("feedback", ['feedback_id' => $id])->row_array();
        } else {
            /*             * ********************* */
            $newArray = array();
            $this->db->select('user_feedback');
            $feedBackData = $this->db->get('feedback')->result();
            $feedbackQuestion = array();
            foreach ($feedBackData as $value) {
                $id = $value->user_feedback;
                $array = explode(',', $id);
                $this->db->select('fq.feedback_questions_id,fq.feedback_questions');
                $this->db->where_in('fq.feedback_questions_id', $array);
                $this->db->where('fq.created_by', $userID);
                $questionData = $this->db->get("feedback_questions fq")->result();
                array_push($feedbackQuestion, $questionData);
            }
            $this->db->select('ft.feedback_type_name,ft.created_on,f.user_comment,f.user_mobileno,f.user_emailid,ft.feedback_type_image,f.feedback_id,f.user_feedback');
            $this->db->join('feedback_type ft', 'ft.feedback_type_id = f.rating', 'left');
            /*             * ******************* */
            $this->db->order_by('f.feedback_id', 'ASC');
            $this->db->where('f.created_by', $userID);
            $data = $this->db->get("feedback f")->result();
            for ($i = 0; $i < COUNT($data); $i++) {
                $newFeedbackAll = array();
                $userFeedBack = $data[$i]->user_feedback;
                $feedbackArray = explode(",", $userFeedBack);
                for ($j = 0; $j < COUNT($feedbackQuestion[$i]); $j++) {
                    if (!empty($feedbackQuestion[$i][$j]->feedback_questions_id)) {
                        if (in_array($feedbackQuestion[$i][$j]->feedback_questions_id, $feedbackArray)) {
                            array_push($newFeedbackAll, $feedbackQuestion[$i][$j]->feedback_questions);
                        }
                    }
                }
                $data[$i]->feedback_questions = implode(",", $newFeedbackAll);
            }
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
        if (!empty($input)) {
            if (!empty($input['user_feedback'])) {
                $user_feedback = implode(",", $input['user_feedback']);
                $input['user_feedback'] = $user_feedback;
                $data = $this->db->insert('feedback', $input);
            } else {
                $response['status'] = FALSE;
                $response['error'] = 'Input feedback is blank';
                return $response;
            }

            if (!empty($data)) {
                $response['status'] = TRUE;
                $id = $this->db->insert_id();
                $q = $this->db->get_where('feedback', array('feedback_id' => $id));
                $response['data'] = $q->row();
            } else {
                $response['error'] = 'Getting error please try after some time';
            }
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'Input is blank';
        }
        return $response;
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input) {
        $data = $this->db->update('feedback', $input, array('feedback_id' => $id));
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
        $data = $this->db->delete('feedback', array('feedback_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
