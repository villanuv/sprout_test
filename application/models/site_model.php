<?php

class Site_model extends CI_model {

  function get_records() 
  {
    $query = $this->db->get('url');
    return $query->result();
  }

  function get_record() 
  {
    $this->db->where('url', 'http://'.$this->uri->segment(3));
    $query = $this->db->get('url');
    return $query->result();
  }

  function add_record($data)
  {
    $this->db->insert('url', $data);
    return;
  }


}