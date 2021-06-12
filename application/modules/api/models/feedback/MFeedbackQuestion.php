<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/feedback
 * @category        common to all
 * @author          Barun Pandey
 * @date            16 September, 2019, 03:28:00 PM
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
    public function getData($data) {
        $id = (!empty($data['id'])) ? $data['id'] : '';
        $userID = (!empty($data['created_by'])) ? $data['created_by'] : '';
        if (!empty($id)) {
            if (is_numeric($id)) {
                $this->db->distinct();
                $this->db->select('ft.feedback_type_name,fs.string_name as feedback_type_header,ft.feedback_type_id,ft.feedback_type_image,fq.feedback_questions,fq.feedback_questions_id');
                $this->db->from('feedback_type ft');
                $this->db->join('feedback_questions fq', 'fq.string_key_id = ft.string_key_id', 'right');
                $this->db->join('feedback_string fs', 'ft.feedback_type_header = fs.string_key_id', 'right');
                $this->db->where('fq.feedback_questions_id', $id);
                $this->db->where('fs.lang', 'en');
                $this->db->where('ft.lang', 'en');
                $this->db->where('fq.lang', 'en');
                $this->db->where('fq.created_by', $userID);
                $data = $this->db->get("feedback_questions")->result();
            } else {
                $col = (!empty($id[0])) ? $id[0] : 'feedback_questions_id';
                $this->db->distinct();
                $this->db->select('ft.feedback_type_name,fs.string_name as feedback_type_header,ft.feedback_type_id,ft.feedback_type_image,fq.feedback_questions,fq.feedback_questions_id');
//                $this->db->from('feedback_type ft');
                $this->db->join('feedback_questions fq', 'fq.feedback_type_id = ft.string_key_id', 'right');
                $this->db->join('feedback_string fs', 'ft.feedback_type_header = fs.string_key_id', 'right');
                $this->db->where('fq.' . $col, $id[1]);
                if ($col == 'lang') {
                    $this->db->where('fs.lang', $id[1]);
                    $this->db->where('ft.lang', $id[1]);
                    $this->db->where('fq.lang', $id[1]);
                } else {
                    $this->db->where('fs.lang', 'en');
                    $this->db->where('ft.lang', 'en');
                    $this->db->where('fq.lang', 'en');
                }
                $this->db->where('fq.created_by', $userID);
                $data = $this->db->get("feedback_type ft")->result();
            }
        } else {
            $this->db->distinct();
            $this->db->select('ft.feedback_type_name,fs.string_name as feedback_type_header,ft.feedback_type_id,ft.feedback_type_image,fq.feedback_questions,fq.feedback_questions_id');
            $this->db->from('feedback_type ft');
//            $this->db->where('ft.created_by', $userID);
            $this->db->join('feedback_questions fq', 'fq.string_key_id = ft.string_key_id', 'right');
            $this->db->join('feedback_string fs', 'ft.feedback_type_header = fs.string_key_id', 'right');
            $this->db->where('fs.lang', 'en');
            $this->db->where('ft.lang', 'en');
            $this->db->where('fq.lang', 'en');
            $this->db->where('fq.created_by', $userID);
            $data = $this->db->get("feedback_type ")->result();
        }
//        echo $this->db->last_query();
//        die;
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
            /*             * ***************** */
            $arr = array();
            foreach ($data as $key => $feedbackValue) {
                $subarray = array(
                    "id" => $feedbackValue->feedback_questions_id,
                    "value" => $feedbackValue->feedback_questions
                );
                $arr[] = $subarray;
            }
            $i = 0;
            foreach ($data as $key) {
                $new_arr[] = $key->feedback_type_name;
            }
            foreach ($data as $key) {
                $new_arr1[] = $key->feedback_type_image;
            }
            foreach ($data as $key) {
                $new_arr2[] = $key->feedback_type_id;
            }
            foreach ($data as $key) {
                $new_arr3[] = $key->feedback_type_header;
            }
            /*             * ******** */
            $result = array();
            foreach ($new_arr2 as $id => $key) {
                $result[$key] = array(
                    'type' => $new_arr[$id],
                    'name' => $new_arr1[$id],
                    'feedback_type_header' => $new_arr3[$id],
                    'id' => $new_arr2[$id],
                );
            }
            /*             * ******** */
            $uniq_arr = array_combine($new_arr1, $new_arr);
            $uniq_arr = array_unique($uniq_arr);
            $feedbackCount = COUNT($uniq_arr);
            foreach ($result as $key => $feedbackValue) {

                $newArray[$i]['feed_back_type_id'] = $feedbackValue['id'];
                $newArray[$i]['feed_back_type'] = $feedbackValue['type'];
                $newArray[$i]['feedback_type_header'] = (!empty($feedbackValue['feedback_type_header'])) ? $feedbackValue['feedback_type_header'] : "";
                $newArray[$i]['feed_back_path'] = $feedbackValue['name'];

                $Id = $feedbackValue['id'];
                $arr = array();
                foreach ($data as $key => $feedbackValue) {
                    if ($Id == ($feedbackValue->feedback_type_id)) {
                        $subarray = array(
                            "id" => $feedbackValue->feedback_questions_id,
                            "value" => $feedbackValue->feedback_questions
                        );
                        $arr[] = $subarray;
                    }
                }

                $newArray[$i]['feed_back_question_count'] = COUNT($arr);
                $newArray[$i]['feed_back_question'] = $arr;
                $i++;
            }
//            die;
            $response['total'] = $feedbackCount;
            $response['data'] = $newArray;

            /*             * ***************** */
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'No record found';
        }
        return $response;
    }

    /**
     * Get All Data f   rom this method.
     *
     * @return Response
     */
    public function insertData($input) {
        if (!empty($input)) {
            if (!empty($input['feedback_question'])) {

                foreach ($input['feedback_question'] as $questionData) {
                    $sql = "SELECT `string_key_id` FROM `feedback_questions` WHERE `string_key_id` = (SELECT MAX(`string_key_id`) from feedback_questions)";
                    $query = $this->db->query($sql);
                    $lastIds = $query->row();
                    $stringId = (!empty($lastIds->string_key_id)) ? ($lastIds->string_key_id + 1) : 1;
                    $input['string_key_id'] = $stringId;
                    $input['lang'] = 'en';
                    $input['feedback_questions'] = $questionData;
                    unset($input['feedback_question']);
                    $q = $this->db->get_where('feedback_questions', array('feedback_type_id' => $input['feedback_type_id'], 'feedback_questions' => $input['feedback_questions']));
                    $newData = $q->row();
                    if (!empty($newData)) {
                        $id = $newData->feedback_questions_id;
                        $newArrayName[] = $newData->feedback_questions;
                        if (!empty($id)) {
                            $data = $this->db->delete('feedback_questions', array('feedback_questions_id' => $id));
                        }
                    } else {
                        $data = $this->db->insert('feedback_questions', $input);
                    }
                }
            }
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            if (!empty($id)) {
                $q = $this->db->get_where('feedback_questions', array('feedback_questions_id' => $id));
                $response['data'] = $q->row();
            } else {
                $response['status'] = TRUE;
                $response['data'] = 'Record deleted successfully';
            }
        } else {
            $response['status'] = FALSE;
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
        $data = $this->db->update('feedback_questions', $input, array('feedback_questions_id' => $id));
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
        $data = $this->db->delete('feedback_questions', array('feedback_questions_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
