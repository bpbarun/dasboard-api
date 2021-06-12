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
class mFeedbackType extends CI_Model {

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
            if (is_numeric($id)) {
                /* $this->db->where('created_by', $userID);
                  $data = $this->db->get_where("feedback_type", ['feedback_type_id' => $id])->row_array(); */
                /*                 * ********************************************************** */
                $this->db->select('ft.*,fs.string_name as feedback_type_header');
                $this->db->from('feedback_type ft');
                $this->db->join('feedback_string fs', 'fs.string_key_id = ft.feedback_type_header', 'left');
                $this->db->where('ft.lang', 'en');
                $this->db->where('ft.created_by', $userID);
//                $data = $this->db->get("feedback_type")->result();
                $data = $this->db->get_where("feedback_type", ['ft.feedback_type_id' => $id])->row_array();
                /*                 * ********************************************************* */
            } else {
                $col = (!empty($id[0])) ? $id[0] : 'feedback_type_id';
                $this->db->where('ft.created_by', $userID);
                for ($i = 0; $i < COUNT($id); $i++) {
                    $this->db->where('ft.' . $id[$i], $id[++$i]);
                    if ($id[0] == 'lang') {
                        $this->db->where('fs.lang', $id[1]);
                    }
                }
                /* $data = $this->db->get("feedback_type")->result(); */
                /*                 * ***************************** */
                $this->db->select('ft.*,fs.string_name as feedback_type_header');
                $this->db->from('feedback_type ft');
                $this->db->join('feedback_string fs', 'fs.string_key_id = ft.feedback_type_header', 'left');
                $this->db->where('ft.created_by', $userID);
                $data = $this->db->get()->result();
                /*                 * ****************************** */
            }
        } else {
            $this->db->select('ft.*,fs.string_name as feedback_type_header');
            $this->db->from('feedback_type ft');
            $this->db->join('feedback_string fs', 'fs.string_key_id = ft.feedback_type_header', 'left');
            $this->db->where('ft.lang', 'en');
            $this->db->where('fs.lang', 'en');
            $this->db->where('ft.created_by', $userID);
            $data = $this->db->get("feedback_type")->result();
            /*             * ********************* */
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
     * Get All Data from this method.
     *
     * @return Response
     */
    public function insertData($input) {
        $sql = "SELECT `string_key_id` FROM `feedback_type` WHERE `string_key_id` = (SELECT MAX(`string_key_id`) from feedback_type)";
        $query = $this->db->query($sql);
        $lastIds = $query->row();
        $stringId = (!empty($lastIds->string_key_id)) ? ($lastIds->string_key_id + 1) : 1;
        $input['string_key_id'] = $stringId;
        $data = $this->db->insert('feedback_type', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('feedback_type', array('feedback_type_id' => $id));
            $data = $q->row();
            if (!empty($data)) {
                $response['data'] = $data;
                $feedbackType = $response['data']->feedback_type_header;
                $sql = "SELECT `string_key_id` FROM `feedback_string` WHERE `string_key_id` = (SELECT MAX(`string_key_id`) from feedback_string)";
                $query = $this->db->query($sql);
                $lastId = $query->row();
                $displayName = (!empty($lastId->string_key_id)) ? ($lastId->string_key_id + 1) : 1;
                $feedabckStringData = array('string_name' => $feedbackType,
                    'string_key_id' => $displayName,
                    'created_by' => $response['data']->created_by,
                    'lang' => 'en'
                );
                $insertData = $this->db->insert('feedback_string', $feedabckStringData);
                if (!empty($insertData)) {
                    $updatedData = array('feedback_type_header' => $displayName);
                    $dataUpdate = $this->db->update('feedback_type', $updatedData, array('feedback_type_id' => $id));
                }
            }
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
        if (!empty($input['feedback_type_header'])) {
            $this->db->select('feedback_type_header');
            $this->db->where('feedback_type_id', $id);
            $this->db->where('modify_by', $input['modify_by']);
            $this->db->where('lang', 'en');
            $selectQuestionDataId = $this->db->get('feedback_type')->row();
            $data = $this->db->update('feedback_string', array('string_name' => $input['feedback_type_header']), array('string_key_id' => $selectQuestionDataId->feedback_type_header, 'lang' => 'en'));
            unset($input['feedback_type_header']);
        }
        $data = $this->db->update('feedback_type', $input, array('feedback_type_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['error'] = 'No row affected in database';
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id) {

        $this->db->select('feedback_type_header');
        $this->db->where('string_key_id', $id);
        $this->db->where('lang', 'en');
        $selectQuestionDataId = $this->db->get('feedback_type')->row();
        $data = $this->db->delete('feedback_type', array('string_key_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $this->db->delete('feedback_questions', array('feedback_type_id' => $id));
            $this->db->delete('feedback_string', array('string_key_id' => $selectQuestionDataId->feedback_type_header));
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
