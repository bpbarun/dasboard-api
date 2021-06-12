<?php

header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This class is used for Crud operation over token 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/Api/case
 * @category        Api
 * @author          Barun Pandey
 * @date            26 March, 2021, 04:11:00 PM
 * @version         1.0.0
 */
class CourtCase extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('court/mCourtCase');
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
            if (is_numeric($id)) {
                $response = $this->mCourtCase->getData($id);
            } else {
                $data = explode(":", $id);
                $response = $this->mCourtCase->getData($data);
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function selectR_post($id = 0) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mCourtCase->getActiveToken($id);
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
    public function getCases_get($id = 0) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (is_numeric($id)) {
                $response = $this->mCourtCase->getCases($id);
            } 
            //echo $this->db->last_query(); die;
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
    public function getRunningCases_get($id = 0) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (is_numeric($id)) {
                $response = $this->mCourtCase->getRunningCases($id);
            } 
            //echo $this->db->last_query(); die;
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
            $response = $this->mCourtCase->insertData($input);
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
            $response = $this->mCourtCase->updateData($id, $input);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    public function updateRunningCase_put() {
        $headerData = $this->input->request_headers();
        $caseNo = $headerData['case_no'];
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->put();
            $caseNo =base64_encode($caseNo);
            $response = $this->mCourtCase->updateRunningCase($caseNo, $input);
            // echo $this->db->last_query(); die;
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
            $response = $this->mCourtCase->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}
