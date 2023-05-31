<?php 

/**
 * MODELO PARA LOS TICKETS
 */
class Ticket extends CI_Model
{
	
	public function insert($data){
		$tipo_soporte = $data['tipo_soporte'];
		$anydesk = $data['anydesk'];
		$usuario = $data['usuario'];
		$descripcion = $data['descripcion'];
		$encargado = $data['encargado'];
		$estado = $data['estado'];
		$fecha_respuesta = $data['fecha_respuesta'];
		$img = $data['img'];
		$area = $data['area'];

		$sql = "INSERT INTO tickets(tipo_soporte,anydesk,usuario,descripcion,encargado,estado,fecha_creacion,fecha_respuesta,prioridad,img,area) VALUES('$tipo_soporte','$anydesk',$usuario,'$descripcion',$encargado,'$estado',SYSDATETIME(),$fecha_respuesta,'','$img','$area')";
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}

	public function get_all_tickets($area,$usu){
		$sql = "SELECT tk.usuario,tk.prioridad, tk.id_ticket,tk.tipo_soporte,en.nombres AS encargado,us.nombres AS nom_usu,tk.fecha_creacion,tk.estado FROM tickets tk 
		INNER JOIN terceros us ON us.nit_real = tk.usuario 
		LEFT JOIN terceros en ON en.nit_real = tk.encargado
		WHERE tk.area IN('".$area."')
		--AND tk.usuario = ".$usu."
		ORDER BY tk.fecha_creacion DESC
		";
		return $this->db->query($sql);
	}

	public function get_all_tickets_by_user($user){
		$sql = "SELECT tk.usuario,tk.prioridad, tk.id_ticket,tk.tipo_soporte,en.nombres AS encargado,us.nombres AS nom_usu,tk.fecha_creacion,tk.estado FROM tickets tk 
		INNER JOIN terceros us ON us.nit_real = tk.usuario 
		LEFT JOIN terceros en ON en.nit_real = tk.encargado
		WHERE tk.usuario = ".$user."
		ORDER BY tk.fecha_creacion DESC
		";
		return $this->db->query($sql);
	}

	public function update_ticket($data,$ticket)
	{
		$this->db->where('id_ticket',$ticket);
		
		if ($this->db->update('tickets',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function get_ticket_by_id($ticket)
	{
		$sql = "SELECT top 1 tk.img,tk.respuesta,tk.descripcion,tk.anydesk, tk.id_ticket,tk.tipo_soporte,(us.primer_nombre+' '+us.primer_apellido) AS usuario,
				t.e_mail as correo_u,
				tk.fecha_creacion,(en.primer_nombre+' '+en.primer_apellido) AS encargado,
				m.e_mail as correo_enc 
				FROM tickets tk
				LEFT JOIN terceros_nombres us ON us.nit = tk.usuario 
				LEFT JOIN terceros_nombres en ON en.nit = tk.encargado
				LEFT JOIN CRM_contactos t ON t.nit = tk.usuario
				LEFT JOIN CRM_contactos m ON m.nit = tk.encargado
				WHERE tk.id_ticket = $ticket
				ORDER BY tk.fecha_creacion DESC";
		return $this->db->query($sql);
	}

	public function get_respuesta($ticket)
	{
		$sql = "SELECT respuesta FROM tickets WHERE id_ticket = ".$ticket;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}
}


?>