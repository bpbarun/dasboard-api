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
 * @date            25th September, 2019, 03:14:00 PM
 * @version         1.0.0
 */
class Multilingual extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('multilingual/mMultilingual');
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
            $data['created_by'] = $responseData['data']['id'];
            if (is_numeric($id)) {
                $data['id'] = $id;
                $response = $this->mMultilingual->getData($data);
            } else {
                $data['id'] = explode(":", $id);
                $response = $this->mMultilingual->getData($data);
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function getLanguage_get() {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
//            $data['created_by'] = $responseData['data']['id'];
//            if (is_numeric($id)) {
                $response = $this->mMultilingual->getActiveLanguage();
//            } else {
//                $data['id'] = explode(":", $id);
//                $response = $this->mMultilingual->getData($data);
//            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function selectR_post() {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $inputData = json_decode(file_get_contents('php://input'), TRUE);
            $inputData['created_by'] = $responseData['data']['id'];
            $response = $this->mMultilingual->selectR($inputData);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function add_post() {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $inputData = json_decode(file_get_contents('php://input'), TRUE);
            $inputData['created_by'] = $responseData['data']['id'];
//            print_r($inputData); die;

            $response = $this->mMultilingual->insert($inputData);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    
     /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function delete_post() {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $inputData = json_decode(file_get_contents('php://input'), TRUE);
            $inputData['created_by'] = $responseData['data']['id'];
//            print_r($inputData); die;

            $response = $this->mMultilingual->deleteData($inputData);
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
            $response = $this->mMultilingualConfig->insertData($input);
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
            $response = $this->mMultilingualConfig->updateData($id, $input);
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
            $response = $this->mMultilingual->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}
