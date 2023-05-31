<?php 

/**
 * 
 */
class NotFound extends CI_Controller
{
	/*Metodo que muestra mensaje de error 404 personalzado*/
	public function index()
	{
		$this->load->view("err/err_404");
	}
}


 ?>