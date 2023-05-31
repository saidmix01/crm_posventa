<?php
/*
 *
 * 
 */
class testServerSide extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ServerSideModel');

    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $data = [];
        $data['content_title']     = 'News - List';
        $this->load->view('testServerSide');
    }

    public function news_datatable()
    {
        $arrayList = [];
        $result     = $this->ServerSideModel->getRows($this->input->get());
        $i             = $this->input->get('start');
        
        foreach ($result as $row ) {
            $action = '
			<a href="' . base_url('news/edit?id=' . $row->id_ausen) . '" class="btn btn-sm btn-primary">
              <i class="fe-edit"></i> Edit</a>
			<button name="deleteButton" data-id="' . $row->id_ausen . '" class="btn btn-sm btn-danger">
              <i class="fe-trash"></i> Delete</button>
			';
            $arrayList[] = [
                ++$i,
                $row->id_ausen,
                $row->empleado,
                $row->descripcion,
                $action
            ];
        }
        
        $output = array(
            "draw"                 => $this->input->get('draw'),
            "recordsTotal"         => $this->ServerSideModel->countAll(),
            "recordsFiltered"    => $this->ServerSideModel->countFiltered($this->input->get()),
            "data"                 => $arrayList,
        );

        echo json_encode($output);
    }
}
