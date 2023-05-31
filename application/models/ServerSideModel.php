<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ServerSideModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

        $this->is_admin       = ($this->session->userdata('login_role') == "admin") ? true : false;
        $this->login_id       = $this->session->userdata('login_id');
        //
        $this->table          = 'postv_ausentismos';
        $fields               = $this->db->list_fields($this->table);
        $this->column_order   = $this->getColumnOrder($fields);
        $this->column_search  = $this->getColumnSearch($fields);
    }

    public function getColumnOrder($fields)
    {
        $list         = [];
        $list[0]     = null;
        foreach ($fields as $field) {
            $list[] = $field;
        }
        return $list;
    }

    public function getColumnSearch($fields)
    {
        $list         = [];
        foreach ($fields as $field) {
            $list[] = $field;
        }
        return $list;
    }

    public function getRows($postData)
    {
        $this->_get_datatables_query($postData);

        $limiteFinal = (integer) $postData['length'] + $postData['start'];
        $sQuery = "SELECT ROW_NUMBER() OVER (order by a1.id_ausen asc ) as rn, a1.* FROM dbo.postv_ausentismos as a1 inner join dbo.terceros t on t.nit=a1.empleado";

        $sQueryFilter ="SELECT s.*  FROM ( $sQuery) as s WHERE s.rn >= ".$postData['start']." AND s.rn <= ".$limiteFinal;

        /* $sQueryFilter = "SELECT * FROM (SELECT RANK() OVER (ORDER BY id_ausen desc) as ranking, a.* FROM postv_ausentismos a )s WHERE s.ranking BETWEEN 1 AND 10"; */

        $query = $this->db->query($sQueryFilter);
        /* print_r($this->db->last_query());die; */
        return $query->result();
    }

    public function countAll()
    {
        return $this->db->count_all($this->table);
    }

    public function countFiltered($postData)
    {
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData)
    {
        $this->db->from($this->table);
        
        $i = 0;
        // loop searchable columns 
        foreach ($this->column_search as $item) {
            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                } else {
                    $this->db->or_like($item, $postData['search']['value']);
                }
                // last loop
                if (count($this->column_search) - 1 == $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}
