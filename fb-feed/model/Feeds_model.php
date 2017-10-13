<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Feeds_model extends CRM_Model
{
	public $feeds_limit = 15;
	public $feeds_peruser_limit = 30;
	public $note_comments_limit = 6;
	function __construct()
	{
		parent::__construct();
		$this->load->model('staff_model');
	}

	public function get_leads_details($id, $noteid = '')
	{
		if($id == '' || !$id) return false;
		$_staff_id = get_staff_user_id();
		$this->load->model('leads_model');
		$_cond = $limit = "";
		if(!empty($noteid)){
			$_cond = "and `ln`.`id` = {$noteid}";
		}else{
			$limit = "Limit 1";
		}
		$sql = "SELECT res.* FROM 
		(
		SELECT `ln`.id,`ln`.leadid,`ln`.staffid,`ln`.tousertype,`ln`.description,`ln`.dateadded,IFNULL(`ln`.datemodified,`ln`.dateadded) as datemodified,'1' as note_type,`ln`.dateadded as duedate FROM `tblleadnotes` as `ln` 
		WHERE `ln`.`leadid` = {$id} ".$_cond."
		UNION 
		SELECT id,rel_id as leadid,addedfrom as staffid,'4' as tousertype,description,dateadded,dateadded as datemodified,'2' as note_type,starttime as duedate FROM tblstafftasks
		WHERE rel_type = 'lead' AND rel_id = {$id}
		) as res 
		WHERE 1 AND res.staffid > 0 ORDER BY res.datemodified DESC ".$limit;
		$noteList = $this->db->query($sql)->result_array();
		// echo $this->db->last_query();
		$noteListWithCmt = array();
		if($noteList && is_array($noteList) && count($noteList) > 0){
			foreach($noteList as $noteDetails){
				$details 				= array();
				$details['noteDetails'] = $noteDetails;
				$details['comments'] 	= array();
				$details['likelist'] 	= array();
				$details['attachments'] = array();
				$details['email_threads'] 	= array();
				if($noteDetails['note_type'] == 1){
					$details['likelist']    = $this->leads_model->get_note_likes($noteDetails['id']);
					$details['attachments'] = $this->leads_model->get_note_attachments($noteDetails['id']);;
					$details['email_threads'] = $this->leads_model->get_note_email_threads($noteDetails['id']);;
					$details['totCmt'] 		= total_rows('tblleadnotecomments', array('noteid' => $noteDetails['id']));
					if ($details['totCmt'] > 0) {
						$sort = 'asc';
						if($details['totCmt'] > 6){
							$sort = 'desc';
						}
						$details['comments'] = $this->get_lead_note_comments($noteDetails['id'], 0, $sort);
					}
				}
				$noteListWithCmt[] = $details;
			}
		}
		return $noteListWithCmt;
	}

	public function get_clients_details($id, $noteid = '')
	{
		if($id =='' || !$id) return false; 
		$_staff_id = get_staff_user_id();
		$this->load->model('clients_model');
		$_cond = $limit = "";
		if(!empty($noteid)){
			$_cond = "AND cn.id = {$noteid}";
		}else{
			$limit = "Limit 1";
		}
		$sql = "SELECT res.* FROM 
		(
		SELECT cn.id,cn.clientid,cn.staffid,cn.tousertype,cn.description,cn.dateadded,IFNULL(cn.datemodified,cn.dateadded) as datemodified,'1' as note_type,cn.dateadded as duedate FROM tblclientnotes as cn 
		WHERE cn.clientid = {$id} ".$_cond."
		UNION 
		SELECT id,rel_id as clientid,addedfrom as staffid,'4' as tousertype,description,dateadded,dateadded as datemodified,'2' as note_type,starttime as duedate FROM tblstafftasks 
		WHERE rel_type = 'customer' AND rel_id = {$id}
		) as res 
		WHERE 1 AND res.staffid > 0 ORDER BY res.datemodified DESC ".$limit;
		$noteList = $this->db->query($sql)->result_array();
        // echo $this->db->last_query();
		$noteListWithCmt = array();
		if($noteList && is_array($noteList) && count($noteList) > 0){
			foreach($noteList as $noteDetails){
				$details                = array();
				$details['noteDetails'] = $noteDetails;
				$details['comments']    = array();
				$details['attachments'] = array();
				if($noteDetails['note_type'] == 1){
					$details['attachments'] = $this->clients_model->get_note_attachments($noteDetails['id']);
					$details['totCmt']      = total_rows('tblclientnotecomments', array('noteid' => $noteDetails['id']));
					$details['likelist']    = $this->clients_model->get_note_likes($noteDetails['id']);
					$details['stafflikecnt']= $this->clients_model->get_staff_likes($noteDetails['id']);
					if ($details['totCmt'] > 0) {
						$sort = 'asc';
						if($details['totCmt'] > 6){
							$sort = 'desc';
						}
						$details['comments'] = $this->get_client_note_comments($noteDetails['id'], 0, $sort);
					}
				}
				$noteListWithCmt[] = $details;
			}
		}
		return $noteListWithCmt;
	}
	public function get_internal_details($group_id, $noteid)
	{
		$this->load->model('internal_model');
		$user_id = get_staff_user_id();
		$noteList = array();
		$_cond = $limit = "";
		if(!empty($noteid)){
			$_cond = "AND ign.id = ".$noteid."";
		}else{
			$limit = "Limit 1";
		}
		$sql = "SELECT ign.*, ig.group_name FROM tblinternalgroup_notes As ign
		JOIN tblinternalgroups As ig ON ig.group_id = ign.group_id
		AND ig.group_id = " . $group_id . " ".$_cond." GROUP BY ign.id ORDER BY ign.modified_date DESC ".$limit;
		$noteList = $this->db->query($sql)->result_array();
		$noteListWithCmt = array();
		if (isset($noteList) && is_array($noteList) && count($noteList) > 0) {
			foreach ($noteList as $noteDetails) {
				$details                 = array();
				$details['noteDetails']  = $noteDetails;
				$details['comments']     = array();
				$details['totNotes']     = count($noteList);
				$details['attachments']  = $this->internal_model->get_note_attachments($noteDetails['id']);
				$details['totCmt']       = total_rows('tblinternalgroup_notes_comments', array('noteid' => $noteDetails['id']));
				$details['likelist']     = $this->internal_model->get_note_likes($noteDetails['id'], $noteDetails['group_id']);
				$details['task']         = $this->internal_model->get_note_task($noteDetails['id'], $noteDetails['group_id']);
				$details['stafflikecnt'] = $this->internal_model->get_staff_likes($noteDetails['id']);
				if ($details['totCmt'] > 0) {
					$sort = 'asc';
					if($details['totCmt'] > 6){
						$sort = 'desc';
					}
					$details['comments'] = $this->get_internal_post_comments($noteDetails['id'], 0, $sort);
				}
				$noteListWithCmt[] = $details;
			}
		}
		return $noteListWithCmt;
	}
	public function get_tasks_details($id)
	{
		$is_admin = is_admin();
		$staffid = get_staff_user_id();
		$this->db->where('id', $id);
		if (!$is_admin && is_staff_logged_in()) {
			$this->db->where('(id IN (SELECT taskid FROM tblstafftaskassignees WHERE staffid='.$staffid.') OR addedfrom='.$staffid.' OR is_public = 1)');
		}
		$task = $this->db->get('tblstafftasks')->row();
		if($task){
			$task->totCmt = total_rows('tblstafftaskcomments', array('taskid' => $task->id));
			$task->comments = array();
			if ($task->totCmt > 0) {
				$sort = 'asc';
				if($task->totCmt > 6){
					$sort = 'desc';
				}
				$task->comments = $this->get_task_comments($id, 0, $sort);
			}
		}
		return $task;
	}
	public function get_tickets_details($id)
	{
		$ticket = $this->tickets_model->get($id);
		if ($ticket) {
			$ticket->statuses = $this->tickets_model->get_ticket_status();
			$ticket->totCmt  = total_rows('tblticketnotes', array('ticketid' => $ticket->ticketid));
			$ticket->comments = array();
			if ($ticket->totCmt > 0) {
				$sort = 'asc';
				if($ticket->totCmt > 6){
					$sort = 'desc';
				}
				$ticket->comments = $this->get_ticket_comments($id, 0, $sort);
			}
		}
		return $ticket;
	}

	public function get_lead_note_comments($noteid, $offset, $sort = '')
	{
		if(empty($sort)){
			$totcmt = total_rows('tblleadnotecomments', array('noteid' => $noteid));
			$sort = 'asc';
			if($totcmt > 6){
				$sort = 'desc';
			}
		}
		$this->db->where('noteid', $noteid);
		$this->db->order_by('dateadded', $sort);
		$offset = ($offset * $this->note_comments_limit);
		$this->db->limit($this->note_comments_limit, $offset);
		return $this->db->get('tblleadnotecomments')->result_array();
	}
	public function get_client_note_comments($noteid, $offset, $sort = '')
	{
		if(empty($sort)){
			$totcmt = total_rows('tblclientnotecomments', array('noteid' => $noteid));
			$sort = 'asc';
			if($totcmt > 6){
				$sort = 'desc';
			}
		}
		$this->db->where('noteid', $noteid);
		$this->db->order_by('dateadded', $sort);
		$offset = ($offset * $this->note_comments_limit);
		$this->db->limit($this->note_comments_limit, $offset);
		return $this->db->get('tblclientnotecomments')->result_array();
	}
	public function get_internal_post_comments($noteid, $offset, $sort = '')
	{
		if(empty($sort)){
			$totcmt = total_rows('tblinternalgroup_notes_comments', array('noteid' => $noteid));
			$sort = 'asc';
			if($totcmt > 6){
				$sort = 'desc';
			}
		}
		$this->db->where('noteid', $noteid);
		$this->db->order_by('dateadded', $sort);
		$offset = ($offset * $this->note_comments_limit);
		$this->db->limit($this->note_comments_limit, $offset);
		return $this->db->get('tblinternalgroup_notes_comments')->result_array();
	}
	public function get_task_comments($id, $offset, $sort = '')
	{
		if(empty($sort)){
			$totcmt = total_rows('tblstafftaskcomments', array('taskid' => $id));
			$sort = 'asc';
			if($totcmt > 6){
				$sort = 'desc';
			}
		}
		$this->db->where('taskid', $id);
		$this->db->order_by('datemodified', $sort);
		$offset = ($offset * $this->note_comments_limit);
		$this->db->limit($this->note_comments_limit, $offset);
		return $this->db->get('tblstafftaskcomments')->result_array();
	}
	public function get_ticket_comments($id, $offset, $sort = '')
	{
		if(empty($sort)){
			$totcmt = total_rows('tblticketnotes', array('ticketid' => $id));
			$sort = 'asc';
			if($totcmt > 6){
				$sort = 'desc';
			}
		}
		$this->db->select('tn.*, tn.ticketnoteid as id, tn.admin as staffid');
		$this->db->where('tn.ticketid', $id);
		$this->db->join('tblstaff ts', 'ts.staffid = tn.admin', 'inner');
		$this->db->order_by('tn.date', $sort);
		$offset = ($offset * $this->note_comments_limit);
		$this->db->limit($this->note_comments_limit, $offset);
		return $this->db->get('tblticketnotes tn')->result_array();
	}
	public function get_reply_comment_attachment($id, $tablename){
		$this->db->select('*,filetype as file_type')
		->where('comment_id',$id)
		->order_by('id','asc');
		$nCommentAttach = $this->db->get($tablename)->result_array();
		return $nCommentAttach;
	}

	public function get_posts($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('tblinternalgroup_notes')->result_array();
	}
}
