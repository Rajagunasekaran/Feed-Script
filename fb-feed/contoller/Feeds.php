<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Feeds extends Admin_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('feeds_model');
		$this->load->model('misc_model');
	}
	public function index($id = '')
	{
		if ($this->input->is_ajax_request()) {

		}
		$leads_details = $this->feeds_model->get_leads_details('',1);
		$data['title']      = 'Feeds';
		$data['bodyclass']  = 'feeds-body';
		$this->load->view('admin/feeds/feeds', $data);
	}
	public function feed_upload($id = ''){
		return false;
	}
}
