<?php

header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This class is used for Crud operation over album
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/Api/user
 * @category        Api
 * @author          Barun Pandey
 * @date            19 August, 2019, 7:00:00 PM
 * @version         1.0.0
 */
class Login extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('auth/mAuth');
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
                $response = $this->mAuth->getData($id);
            } else {
                $data = explode(":", $id);
                $response = $this->mAuth->getData($data);
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
        $input = $this->post();
        $responseData = $this->common->checkLogin($input);
        if (empty($responseData['error'])) {
            /*
              if (hash_equals($hashed_password, crypt($password, $hashed_password))) {
              $last_login = date('Y-m-d H:i:s');
              $token = crypt(substr( md5(rand()), 0, 7));
              $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
              $this->db->trans_start();
              $this->db->where('id',$id)->update('users',array('last_login' => $last_login));
              $this->db->insert('users_authentication',array('users_id' => $id,'token' => $token,'expired_at' => $expired_at));
              if ($this->db->trans_status() === FALSE){
              $this->db->trans_rollback();
              return array('status' => 500,'message' => 'Internal server error.');
              } else {
              $this->db->trans_commit();
              return array('status' => 200,'message' => 'Successfully login.','id' => $id, 'token' => $token);
              }
              }
             */
            $inputData['token_code'] = substr(md5(rand()), 0, 16);
            $inputData['created_on'] = date("Y-m-d H:i:s");
            $inputData['expire_on'] = date("Y-m-d H:i:s", strtotime('+6 hours'));
            $inputData['user_id'] = $responseData['data']['user_id'];
            $inputData['ip'] = $_SERVER['REMOTE_ADDR'];
            $response = $this->mAuth->insertData($inputData);
            if (empty($response['error']))
                $response['data']->user_name = $responseData['data']['user_name'];
//             $response['data']->company_name = (!empty($responseConfigData['data']['company_name'])) ? $responseConfigData['data']['company_name'] : '';
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
            $response = $this->mAuth->updateData($id, $input);
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
            $response = $this->mAuth->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}
