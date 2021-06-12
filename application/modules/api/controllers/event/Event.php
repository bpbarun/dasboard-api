<?php

header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This class is used for Crud operation over album
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/Api/Event
 * @category        Api
 * @author          Barun Pandey
 * @date            17th March, 2019, 07:04:00 PM
 * @version         1.0.0
 */
class Event extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('event/mEvent');
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
            $response = $this->mEvent->getData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

   public function Current_get($id = 0) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mEvent->getCurrentData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
     public function Past_get($id = 0) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mEvent->getPastData($id);
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
            $response = $this->mEvent->insertData($input);
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
            $response = $this->mEvent->updateData($id, $input);
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
            $response = $this->mEvent->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}
