<?php

header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This class is used for Crud operation over album
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/Api/Feedback
 * @category        Api
 * @author          Barun Pandey
 * @date            16th September, 2019, 12:50:00 PM
 * @version         1.0.0
 */
class FeedbackQuestion extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('feedback/mFeedbackQuestion');
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    public function index() {
        echo "called index function";
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function index_get($id = 0) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $data['created_by'] = (!empty($headerData['user_id'])) ? $headerData['user_id'] : $responseData['data']['id'];
            if (is_numeric($id)) {
                $data['id'] = $id;
                $response = $this->mFeedbackQuestion->getData($data);
            } else {
                $data['id'] = explode(":", $id);
                $response = $this->mFeedbackQuestion->getData($data);
            }
            $commonData = $this->common->getFeedbackConfigData($data);
            if (!empty($commonData['data'])) {
                for ($i = 0; $i < COUNT($commonData['data']); $i++) {
                    if ($commonData['data'][$i]->config_type == 'header_text') {
                        $response['header_text'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : '';
                    }
                    if ($commonData['data'][$i]->config_type == 'sub_header_text') {
                        $response['sub_header_text'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : '';
                    }
                    if ($commonData['data'][$i]->config_type == 'logo') {
                        $response['logo'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : '1.png';
                    }
                    if ($commonData['data'][$i]->config_type == 'leave_comment_text') {
                        $response['leave_comment_text'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : 'Leave a comment';
                    }
                    if ($commonData['data'][$i]->config_type == 'save_text') {
                        $response['save_text'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : 'Save';
                    }
                    if ($commonData['data'][$i]->config_type == 'any_suggestion') {
                        $response['comment_hint'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : 'Any Suggestion';
                    }
                    if ($commonData['data'][$i]->config_type == 'contact_no') {
                        $response['mobile_hint'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : 'Contact No';
                    }
                    if ($commonData['data'][$i]->config_type == 'email_Id') {
                        $response['email_hint'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : 'Email Id';
                    }
                    if ($commonData['data'][$i]->config_type == 'cancel_btn') {
                        $response['cancel_btn'] = (!empty($commonData['data'][$i]->string_name)) ? $commonData['data'][$i]->string_name : 'Email Id';
                    }
                }
            } else {
                $response['header_text'] = '';
                $response['sub_header_text'] = '';
                $response['logo'] = '';
                $response['leave_comment_text'] = 'Leave your comment';
                $response['save_text'] = 'Save';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function index_post() {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->post();
            $input['created_by'] = $responseData['data']['id'];
            $response = $this->mFeedbackQuestion->insertData($input);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Update Data from this method.
     *
     * @return Response
     */
    public function index_put($id) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->put();
            $input['modify_by'] = $responseData['data']['id'];
            $response = $this->mFeedbackQuestion->updateData($id, $input);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function index_delete($id) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->put();
            $input['modify_by'] = $responseData['data']['id'];
            $response = $this->mFeedbackQuestion->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}
