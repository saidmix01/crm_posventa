<?php 
                
defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Mpc_model extends CI_Model 
{
    public function getInformeMpc()
    {
      return $this->db->get('v_Informe_mpc');
    }
}

/* End of file Mpc_model.php and path \application\models\Mpc_model.php */
                    
