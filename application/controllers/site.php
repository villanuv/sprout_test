<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Site extends REST_Controller
{
    function all_get()
    {
        $data = array();

        if($query = $this->site_model->get_records())
        {
            foreach($query as $row) {
                $row->imagedb = unserialize($row->imagedb);
            }
        }

        $this->response($query);
    }


    function info_get()
    {
        $data = array();

        if($query = $this->site_model->get_record())
        {
            foreach($query as $row) {
                $data['url'] = $row->url;
                $data['title'] = $row->title;
                $data['description'] = $row->url;
                $data['images'] = unserialize($row->imagedb);
            }
        }

        $this->response($data);
    }

	function info_post()
    {

        $postjson = json_decode(file_get_contents("php://input"), true);
        $url = $postjson['url'];

        $data['url'] = "http://".$url;
        $html = file_get_html($data['url']);

        // title
        $title = $html->find("title", 0);
        $data['title'] = $title->innertext;

        // check for description
        $checkdesc1 = $html->find('meta[name="description"]', 0);
        $checkdesc2 = $html->find('meta[name="Description"]', 0);

        if (is_object($checkdesc1)) {
          $desc = $checkdesc1->content;
        } else if (is_object($checkdesc2))  {
          $desc = $checkdesc2->content;
        } else {
          $desc = 'Patrick did not find a description. There might be something buggy.';
        }

        $data['description'] = $desc;

        // get images
        $imageArray = $html->find('img');
        $images = array();

        foreach ($imageArray as $image)
        {
            array_push($images, $image->src);
        }

        $data['imagedb'] = serialize(array_slice($images, 0, 10));

        // $this->response($data);
        $this->site_model->add_record($data);

    }
    
}