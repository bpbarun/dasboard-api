<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over cort 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/api/cort-case
 * @category        common to all
 * @author          Barun Pandey
 * @date            26 March, 2021, 04:23:00 PM
 * @version         1.0.0
 */
class mCourtCase extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getData($id)
    {
        if (!empty($id)) {
            $data = $this->db->get_where("court_case", ['case_id' => $id])->row_array();
        } else {
            $this->db->select('c.court_name,cs.*');
            $this->db->join('court_case cs', 'cs.court_id = c.court_id', 'right');
            $this->db->order_by('cs.created_on', 'ASC');
            $data = $this->db->get('court c')->result();
        }
        // echo $this->db->last_query(); die;
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
    public function getCases($id)
    {
        $this->db->distinct('c.court_id');
        $this->db->select('c.*,cs.case_no');
        $this->db->join('court_case cs', 'c.court_id = cs.court_id','right');
        $this->db->group_by('c.court_id');
        if(!empty($id)){
            $this->db->where('cs.court_id',$id);
        }
        $this->db->where('DATE(cs.case_date) = DATE(NOW())');
        $this->db->where('cs.is_active',2);
        $data = $this->db->get('court c')->result();
        // if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
            // foreach ($data as $key => $newData) {
                $this->db->select('case_no', NULL, FALSE);
                $this->db->from('court_case', NULL, FALSE);
                $this->db->where('court_id', $id);
                $this->db->where('DATE(case_date) = DATE(NOW())');
                // $this->db->where_not_in('case_no', $newData->case_no);
                $this->db->where('is_active', 1);

                // $this->db->limit(3);
                $this->db->order_by('case_date', 'ASC');
                $nextToken = $this->db->get()->result_array();
                $nData = array();
                foreach ($nextToken as $nexTokenData) {
                    array_push($nData, $nexTokenData['case_no']);
                }
                $nextToken = implode(",", $nData);
                if (!empty($data)) {
                    $response['data'][0]->next_case = $nextToken;;
                }
                else {
                    $response['data'][0]['next_case'] = $nextToken;
                }
            // }
        // } else {
        //     $response['status'] = FALSE;
        //     $response['error'] = 'No record found';
        // }
        return $response;
    }

    public function getRunningCases($id)
    {
        $this->db->select('*', NULL, FALSE);
        $this->db->from('court', NULL, FALSE);
        if(!empty($id)){
            $this->db->where('court_id',$id);
        }
        $this->db->where('court_id IN (SELECT DISTINCT court_id FROM court_case WHERE DATE(case_date) = DATE(NOW()) AND is_active != 4)');
        $data = $this->db->get()->result();
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
            foreach ($data as $key => $newData) {
                $this->db->select('case_no', NULL, FALSE);
                $this->db->from('court_case', NULL, FALSE);
                $this->db->where('court_id', $newData->court_id);
                $this->db->where('DATE(case_date) = DATE(NOW())');
                $this->db->where('is_active', 2);
                $this->db->limit(1);
                $this->db->order_by('case_date', 'ASC');
                $nextToken = $this->db->get()->result_array();
                $nData = array();
                foreach ($nextToken as $nexTokenData) {
                    array_push($nData, $nexTokenData['case_no']);
                }
                $nextToken = implode(",", $nData);
                $response['data'][$key]->case_no = $nextToken;

                $this->db->select('case_no', NULL, FALSE);
                $this->db->from('court_case', NULL, FALSE);
                $this->db->where('court_id', $newData->court_id);
                $this->db->where_not_in('case_no', $newData->case_no);
                $this->db->where('DATE(case_date) = DATE(NOW())');
                $this->db->where('is_active', 1);
                $this->db->limit(3);
                $this->db->order_by('case_date', 'ASC');
                $nextToken = $this->db->get()->result_array();
                $nData = array();
                foreach ($nextToken as $nexTokenData) {
                    array_push($nData, $nexTokenData['case_no']);
                }
                $nextToken = implode(",", $nData);
                $response['data'][$key]->next_case = $nextToken;
            }
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'No record found';
        }
        return $response;
    }
    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function insertData($input)
    {
        $data = $this->db->insert('court_case', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('court_case', array('case_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Update Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input)
    {
        $data = $this->db->update('court_case', $input, array('case_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id)
    {
        $data = $this->db->delete('court_case', array('case_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    public function updateRunningCase($caseNo,$input)
    {
        $data = $this->db->update('court_case', $input, array('case_no' => base64_decode($caseNo)));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
    
}
