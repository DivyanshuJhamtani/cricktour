<?php
class Tournaments extends MY_Controller {

	function __construct() 
	{
		parent::__construct();
		$this->load->model('tournaments_model');
	}

	function index()
	{
		$data = array();
		$tournaments_records = $this->tournaments_model->tournaments_info()->get_all('tournaments');

		$data['tournaments_records'] = $tournaments_records;

	    $data['main'] = 'admin/tournaments/tournaments_list';
	    $data['js_function'] = array('tournaments_list');

		$this->load->view('admin/template',$data);
		
	}
	
	function add_tournaments()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Tournament Name', 'trim|required');
		$this->form_validation->set_rules('city_id', 'City', 'trim|required');
		$this->form_validation->set_rules('ground_id', 'Ground', 'trim|required');
		$this->form_validation->set_rules('organiser_name', 'Organiser Name', 'trim|required');
		$this->form_validation->set_rules('organiser_mobile_number', 'Organiser Mobile Number', 'trim|required|numeric');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('tournament_category', 'Tournament Category', 'trim|required');
		$this->form_validation->set_rules('ball_type', 'Ball Type', 'trim|required');
		$this->form_validation->set_rules('match_type', 'Match Type', 'trim|required');
		// $this->form_validation->set_rules('logo', 'Logo', 'required');
		// $this->form_validation->set_rules('banner', 'Banner', 'required');
		$this->form_validation->set_rules('more_details', 'More Details', 'trim');
		

		if($this->form_validation->run() == TRUE)
	    {
			
	    	$name = $this->input->post('name');
			$city_id = $this->input->post('city_id');
			$ground_id = $this->input->post('ground_id');
			$organiser_name = $this->input->post('organiser_name');
			$organiser_mobile_number = $this->input->post('organiser_mobile_number');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$tournament_category = $this->input->post('tournament_category');
			$ball_type = $this->input->post('ball_type');
			$match_type = $this->input->post('match_type');
			$logo = $this->input->post('logo');
			$banner = $this->input->post('banner');
			$more_details = $this->input->post('more_details');

			$config['upload_path']='E:/xampp/htdocs/cricktour/images/uploads/';
			$config['allowed_types']= 'gif|jpg|png';
			$config['max_size']= 10000;
			
			$this->load->library('upload', $config);
			
			//Check if logo in uploaded
			if(!$this->upload->do_upload('logo'))
			{
				$data['error'] = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $data);
					redirect('admin/tournaments/add_tournaments');
			}
			else
			{
				$upload_data=$this->upload->data();
				$logo=$upload_data['file_name'];
			}

			//Check if banner is uploaded or not
			if(!$this->upload->do_upload('banner'))
			{
					$data['error'] = array('error' => $this->upload->display_errors());
						$this->session->set_flashdata('error', $data);
						redirect('admin/tournaments/add_tournaments');
			}
			else
			{
				$upload_data=$this->upload->data();
				$banner=$upload_data['file_name'];
			}
				//inserting data in table if images are uploaded successfully
	    		$data = array('name'=>$name,'city_id'=>$city_id,'ground_id'=>$ground_id,'organiser_name'=>$organiser_name,'organiser_mobile_number'=>$organiser_mobile_number,'start_date'=>$start_date,'end_date'=>$end_date,'tournament_category'=>$tournament_category,'ball_type'=>$ball_type,'match_type'=>$match_type,'logo'=>$logo,'banner'=>$banner,'more_details'=>$more_details);
	    	if($this->tournaments_model->insert($data))
			{
				$this->session->set_flashdata('success', 'The tournaments info have been successfully added');
				redirect('admin/tournaments/');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect('admin/tournaments/add_tournaments');
			}
	    }
	    else //if page initial load or form validation false
	    {
	    	$data = array();

	    	$data['main'] = 'admin/tournaments/add_tournaments';

			#$this->load->view('admin/tournaments/add_tournaments',$data);
			$this->load->view('admin/template',$data);
	    }
	}

	function edit_tournaments()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Tournament Name', 'trim|required|is_unique');
		$this->form_validation->set_rules('city_id', 'City', 'trim|required');
		$this->form_validation->set_rules('ground_id', 'Ground', 'trim|required');
		$this->form_validation->set_rules('organiser_name', 'Organiser Name', 'trim|required');
		$this->form_validation->set_rules('organiser_mobile_number', 'Organiser Mobile Number', 'trim|required|numeric');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('tournament_category', 'Tournament Category', 'trim|required');
		$this->form_validation->set_rules('ball_type', 'Ball Type', 'trim|required');
		$this->form_validation->set_rules('match_type', 'Match Type', 'trim|required');
		$this->form_validation->set_rules('logo', 'Logo', 'trim|required');
		$this->form_validation->set_rules('banner', 'Banner', 'trim|required');
		$this->form_validation->set_rules('more_details', 'More Details', 'trim');

		if($this->form_validation->run() == TRUE)
	    {
	    	$hash_tournaments_id = $this->input->post('tournaments_id');
				
	    	//check hash if the user edit it

	    	$id = get_attr_id($hash_tournaments_id);
	    	$hash = get_attr_hash($hash_tournaments_id);

	    	$this->permission->check_form_id_hash($id,$hash);

	    	$name = $this->input->post('name');
			$city_id = $this->input->post('city_id');
			$ground_id = $this->input->post('ground_id');
			$organiser_name = $this->input->post('organiser_name');
			$organiser_mobile_number = $this->input->post('organiser_mobile_number');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$tournament_category = $this->input->post('tournament_category');
			$ball_type = $this->input->post('ball_type');
			$match_type = $this->input->post('match_type');
			$logo = $this->input->post('logo');
			$banner = $this->input->post('banner');
			$more_details = $this->input->post('more_details');
			

	    	$data = array('name'=>$name,'city_id'=>$city_id,'ground_id'=>$ground_id,'organiser_name'=>$organiser_name,'organiser_mobile_number'=>$organiser_mobile_number,'start_date'=>$start_date,'end_date'=>$end_date,'tournament_category'=>$tournament_category,'ball_type'=>$ball_type,'match_type'=>$match_type,'logo'=>$logo,'banner'=>$banner,'more_details'=>$more_details);
	    	if($this->tournaments_model->update($id,$data))
			{
				$this->session->set_flashdata('success', 'The tournaments info have been successfully updated');
				redirect("tournaments/edit_tournaments/$id/$hash");
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect("tournaments/edit_tournaments/$id/$hash");
			}
	    }
	    else //if page initial load or form validation false
	    {
	    	$id = $this->uri->segment(4);
	    	//means come from tournaments list

	    	if($this->uri->segment(4))
	    	{
	    		//$this->permission->check_id_hash($id);
	    	}

	    	//means come from validation error

	    	if($this->input->post('tournaments_id'))
	    	{
	    		$hash_tournaments_id = $this->input->post('tournaments_id');

		    	//check hash if the user edit it

		    	$id = get_attr_id($hash_tournaments_id);
		    	$hash = get_attr_hash($hash_tournaments_id);

		    	$this->permission->check_form_id_hash($id,$hash);
	    	}

	    	$data = array();			

	    	$tournaments_records = $this->tournaments_model->get($id);

	    	$data['tournaments_records'] = $tournaments_records;

	    	$data['main'] = 'admin/tournaments/edit_tournaments';

			$this->load->view('admin/template',$data);
	    }
	}

	function ajax_delete_tournaments()
	{
		$this->permission->is_ajax();

		$ajax_tournaments_id = $this->input->post('tournaments_id');

		//get the tournaments_id

		$id = get_attr_id($ajax_tournaments_id);

		//get the hash

		$hash = get_attr_hash($ajax_tournaments_id);

		//check the hash

		$this->permission->check_ajax_id_hash($id,$hash);

		if($this->tournaments_model->delete($id))
		{
			echo '1';
		}
		else
		{
			echo '2';
		}

	}

}
?>