<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/album
 * @category        common to all
 * @author          Barun Pandey
 * @date            25 September, 2019, 05:56:00 PM
 * @version         1.0.0
 */
class mMultilingual extends CI_Model {

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
    public function selectR($input) {
        $selectColumn = explode(',', $input['select']);
        if (!empty($input['select'])) {
            $select = $input['select'];
        }
        if (!empty($input['where'])) {
            $whereCondition = $input['where'];
        }
        if (!empty($input['table'])) {
            $table = $input['table'];
        }
        if (!empty($input['created_by'])) {
            $createdBy = $input['created_by'];
        }
        $this->db->select($select);
        if (!empty($input['where'])) {
            $this->db->where($whereCondition);
        }
        if (!empty($createdBy)) {
            $this->db->where('created_by', $createdBy);
        }
        $this->db->order_by($selectColumn[1], 'ASC');
        $data = $this->db->get($table)->result_array();
        if (!empty($data)) {
            $columnName = $selectColumn[0];
            $columnID = $selectColumn[1];
            for ($i = 0; $i < COUNT($data); $i++) {
                $currentID = $data[$i]['string_key_id'];
                $data[$i]['string_value'] = $data[$i][$columnName];
                $data[$i]['string_id'] = $data[$i][$columnID];
                for ($j = $i + 1; $j < COUNT($data); $j++) {
                    if ($currentID == $data[$j]['string_key_id'] && $data[$j]['lang'] != 'en') {
                        $lang = $data[$j]['lang'];
                        $data[$i][$lang] = $data[$j][$columnName];
                        if (isset($data[$j])) {
                            array_splice($data, $j, 1);
                            $j = $i;
                        }
                    }
                }
                unset($data[$i][$selectColumn[0]]);
                unset($data[$i][$selectColumn[1]]);
            }
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        return $response;
    }

    public function getActiveLanguage($data = 0) {
        $this->db->select('config_value');
        $this->db->where('config_key', 'active_language');
        $selectData = $this->db->get('multilingual_config')->row();
        $aa = json_decode($selectData->config_value);
        $a = array();
        $b = array();
        foreach (json_decode($selectData->config_value) as $aaaa => $vvvv) {
            array_push($a, $aaaa);
            array_push($b, $vvvv);
        }
        $main = array_combine($a, $b);
        $keys = array_keys($main);
        for ($i = 0; $i < count($keys); ++$i) {
            $lanArray[$i]['lang_code'] = $keys[$i];
            $lanArray[$i]['lang_name'] = $main[$keys[$i]];
        }
        if (!empty($selectData)) {
            $response['status'] = TRUE;
            $response['data'] = $lanArray;
        } else {
            $response['error'] = $this->db->_error_message();
        }
        return $response;
    }

    /**
     * Insert the given data in database
     *
     * @return Response
     */
    public function insert($input) {
        foreach ($input['table'] as $key => $value) {
            $this->db->select('string_key_id,created_by');
            $this->db->where($value[0], $input['data']['enName']);
            $this->db->where($value[1], $input['data']['id']);
            $selectData = $this->db->get($key)->row();
            if (!empty($selectData)) {
                $inputVal = $input['data']['textValue'];
                foreach ($inputVal as $keys => $val) {
                    if (!empty($val)) {
                        $this->db->select($value[1]);
                        $this->db->where('string_key_id', $selectData->string_key_id);
                        $this->db->where('created_by', $selectData->created_by);
                        $this->db->where('lang', $keys);
                        $selectLangValue = $this->db->get($key)->row();
                        $colID = $value[1];
                        if (!empty($selectLangValue)) {
                            $data = $this->db->update($key, array($value[0] => $val), array($value[1] => $selectLangValue->$colID));
                        } else {
                            $insertData = array(
                                'lang' => $keys,
                                $value[0] => $val,
                                'string_key_id' => $selectData->string_key_id,
                                'created_by' => $selectData->created_by,
                            );
                            $data = $this->db->insert($key, $insertData);
                            $id = $this->db->insert_id();
                            if ($key == 'feedback_questions') {
                                $this->db->select('feedback_type_id');
                                $this->db->where('string_key_id', $selectData->string_key_id);
                                $this->db->where('created_by', $selectData->created_by);
                                $this->db->where('lang', 'en');
                                $this->db->where('feedback_questions_id', $input['data']['id']);
                                $selectQuestionDataId = $this->db->get($key)->row();
                                $data = $this->db->update('feedback_questions', array('feedback_type_id' => $selectQuestionDataId->feedback_type_id), array('feedback_questions_id' => $id));

//                                echo $this->db->last_query();
                            }
                            if ($key == 'feedback_type') {
                                $this->db->select('feedback_type_header,feedback_type_image');
                                $this->db->where('string_key_id', $selectData->string_key_id);
                                $this->db->where('created_by', $selectData->created_by);
                                $this->db->where('lang', 'en');
                                $this->db->where('feedback_type_id', $input['data']['id']);
                                $selectQuestionDataId = $this->db->get($key)->row();
                                $data = $this->db->update('feedback_type', array('feedback_type_header' => $selectQuestionDataId->feedback_type_header, 'feedback_type_image' => $selectQuestionDataId->feedback_type_image), array('feedback_type_id' => $id));
//                                echo $this->db->last_query();
                            }
                        }
                    }
                }
            }
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = $this->db->_error_message();
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($input) {
        foreach ($input['table'] as $key => $value) {
            $this->db->select('string_key_id');
            $this->db->where($value[0], $input['data']['name']);
            $this->db->where($value[1], $input['data']['id']);
            $selectData = $this->db->get($key)->row();
            if (!empty($selectData)) {
                $dltId = $selectData->string_key_id;
                $this->db->where_in('string_key_id', $dltId);
                $data = $this->db->delete($key);
            } else {
                continue;
            }
        }
        echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
