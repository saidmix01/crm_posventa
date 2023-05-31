<?php 

/**
 * 
 */
class Informes extends CI_Model
{
	
	public function get_total_dias()
	{
		$sql="SELECT DAY(DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE())+1,0))) AS 'ultimo_dia'";
		$result = $this->db->query($sql);
	    if($result->num_rows() > 0){
          return $result->row();
	    }else{
	      return null;
	   }
	}

	public function get_dias_actual()
	{
		$sql = "SELECT DAY(GETDATE()) AS 'dia'";
		$result = $this->db->query($sql);
	    if($result->num_rows() > 0){
          return $result->row();
	    }else{
	      return null;
	   }
	}

	public function get_fecha_actual()
	{
		$sql = "SELECT CONVERT(VARCHAR,GETDATE(),22) AS 'fecha'";
		$result = $this->db->query($sql);
	    if($result->num_rows() > 0){
          return $result->row();
	    }else{
	      return null;
	   }
	}

	public function get_fecha()
	{
		$sql = "SELECT CONVERT(VARCHAR,GETDATE(),23) AS 'fecha'";
		$result = $this->db->query($sql);
	    if($result->num_rows() > 0){
          return $result->row();
	    }else{
	      return null;
	   }
	}

	public function get_nombre_mes_actual(){
		$sql = "SELECT DATENAME(month,GETDATE()) AS mes";
		$result = $this->db->query($sql);
	    if($result->num_rows() > 0){
          return $result->row();
	    }else{
	      return null;
	   }
	}

}


 ?>