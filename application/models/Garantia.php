<?php 

/**
 * MODELO PARA GARANTIAS
 */
class Garantia extends CI_Model
{
	//METODO QUE INSERTA EN LA DB
	public function insert_rel_env_garantias($n_tran,$n_guia,$fecha,$orden)
	{
		$sql = "INSERT INTO relacion_envio_garantias(n_transaccion,n_guia,fecha,orden)
				VALUES ('$n_tran','$n_guia','$fecha','$orden')";
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}
}

 ?>