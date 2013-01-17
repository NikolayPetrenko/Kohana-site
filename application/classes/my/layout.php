<?php


class MY_Layout extends Controller
{
	
	protected $_title 	= '';
	protected $_keywords	= '';
	protected $_description	= '';

	protected $_data		= array();

	public function before()
	{
                Helper_Output::factory()->link_css('bootstrap')
					->link_js('jquery')
					->link_js('bootstrap.min')
                                        ->link_js('common')
					;
                
		$config = Kohana::$config->load('config');
		$this->_title 		= $config->get('Site Title');
		$this->_keywords 	= $config->get('Site Keywords');
		$this->_description     = $config->get('Site Description');
	}


	/*
	*  SEO data
	*/
	public function setTitle($title = '')
	{
		if($title != '') {
			$this->_title = $title;
		}
		return $this;
	}

	public function setKeyword($text = '')
	{
		if($text != '') {
			$this->_keywords = $text;
		}
		return $this;
	}

	public function setDescription($text = '')
	{
		if($text != '') {
			$this->_description = $text;
		}
		return $this;
	}

	/*
	* set partial template
	*/
	public function view($partials = '', $data = array())
	{
		$this->template->content = View::factory($partials);

		if(!empty($data)) {
			$this->setData($data, $this->template->content);
		}

		return $this;
	}

	public function setData($data = array(), $scope = false)
	{
		if(!empty($data)) {
			foreach ($data as $key => $value) {
				if($scope) {
					$scope->$key = $value;
				}
				$this->template->$key = $value;
			}
		}

		$this->_data = $data;

		return $this;
	}

	/*
	* @param $format:: html(default), json
	*/
	public function render($format = 'html')
	{
		 $this->template->title 		= $this->_title;
		 $this->template->keywords 		= $this->_keywords;
		 $this->template->description 	= $this->_description;


		 if(isset($_POST['json']) && $_POST['json'] == 1) {
		 	$format = 'json';
		 }

		 switch($format) {
		 	case 'html': 
		 		$this->response->body($this->template);
		 		break;
		 	case 'json':
		 		header('Content-type: text/json');
				header('Content-type: application/json');
				echo json_encode($this->_data);
		 		break;
		 }
	}
}