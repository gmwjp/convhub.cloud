<?php  
namespace App\Libraries;
class Layout
{
    
    var $obj;
    var $layout;
    var $language;
    function Layout($params)
    {
		if(empty($params[0])){
			$params[0] = "main";
		}
		if(empty($params[1])){
			$params[1] = "JP";
		}
		$this->language = $params[1];
        $this->obj =& get_instance();
        $this->layout = $this->language."/"._device."/layout/".$params[0];
    }

    function setLayout($layout)
    {
        $this->layout = $this->language."/"._device."/layout/".$layout;
    }
    
    function view($view, $data=null, $return=false)
    {

        $loadedData = array();
        $loadedData['content_for_layout'] = $this->obj->load->view($view,$data,true);
        
        if($return)
        {
            $output = $this->obj->load->view($this->layout, $loadedData, true);
            return $output;
        }
        else
        {
            $this->obj->load->view($this->layout, $loadedData, false);
        }
    }
}
?> 