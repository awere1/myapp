<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller
{
    public function display($task_id)
    {
        $data['project_id'] = $this->task_model->get_task_project_id($task_id);
        $data['project_name'] = $this->task_model->get_project_name($data['project_id']);

        $data['task'] = $this->task_model->get_task($task_id);
        $data['main_view'] = "tasks/display";
        $this->load->view('layouts/main', $data);
    }


    // method to insert a new task into the database
    public function create($project_id) 
    {
        if($this->input->post() ==false){
         $data['main_view'] = 'tasks/create_task';
         $this->load->view('layouts/main', $data);
        } else{   
         $data = array(
                'project_id' => $project_id,
                'task_name' => $this->input->post('task_name'),
                'task_body' => $this->input->post('task_body'),
                'due_date' => $this->input->post('due_date')
                );

            if ($this->task_model->create_tasks($data)) {
                $this->session->set_flashdata('tasks_created', 'Your Task has been created');

                //redirect back to the project display page
                redirect("projects/display/" .$project_id);
            } 
            }
    }

	//method to Edit the existing taks in the database
    public function edit($task_id) 
    {
        //Get the existing project ID
         $data['project_id'] = $this->task_model->get_task_project_id($task_id);
         //Get the exisiting project name
         $data['project_name'] = $this->task_model->get_project_name($data['project_id']);
         //Get the exisiting project data
         $data['the_task'] = $this->task_model->get_task_project_data($task_id);

         if($this->input->post() ==false){
        $data['main_view'] = 'tasks/edit_task';
         $this->load->view('layouts/main', $data);
        } else{
         $data = array(
                'project_id' =>  $data['project_id'],
                'task_name' => $this->input->post('task_name'),
                'task_body' => $this->input->post('task_body'),
                'due_date' => $this->input->post('due_date')
                );

            if ($this->task_model->edit_tasks($task_id, $data)) {
                $this->session->set_flashdata('task_edited', 'Your Task has been edited');

                //redirect back to the project display page showing the data that was previously inputed
                redirect("projects/display/" .$data['project_id']);
            }
        }
    }

    public function delete($project_id, $task_id) 
    {
        $this->task_model->delete_task($task_id);
        $this->session->set_flashdata('task_deleted', 'Your Task has been deleted');

        //redirect back to the project display page
        redirect("projects/display/" .$project_id);
    }
}
