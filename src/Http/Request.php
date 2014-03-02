<?php

namespace Fk\Http;

use Fk\Uri\Uri;
use Fk\Route\Router;

Class Request {

	protected $uri;
	protected $route;
	protected $client;
	protected $locale;
	protected $cookieManager;

	public function __construct(Uri $uri) {
		$this->uri = $uri;
		$this->client = new Client();
		$this->locale = new Locale();
		$this->cookieManager = new CookieManager();
	}

	public function getUri() {
		return $this->uri;
	}

	public function getRoute() {
		if(empty($this->route)) {
			$this->route = Router::getRoute($this->getUri());
		}
		return $this->route;
	}

	public function getData($attribut = null, $method = null) {
		$data = $this->inputs();
		return ($attribut === null) ?
			$data : 
			(array_key_exists($attribut, $data) ?
					$data[$attribut] : false
			);
	}

	private function inputs(){
		switch($this->getMethod()){
			case "POST":
				$this->request = $this->cleanInputs($_POST);
				break;
			case "GET":
			case "DELETE":
				$this->request = $this->cleanInputs($_GET);
				break;
			case "PUT":
				parse_str(file_get_contents("php://input"),$_REQUEST);
				$this->request = $this->cleanInputs($_REQUEST);
				break;
			default:
				$this->response('',406);
				break;
		}
	}

	private function cleanInputs($data){
		$clean_input = array();
		if(is_array($data)){
			foreach($data as $k => $v){
				$clean_input[$k] = $this->cleanInputs($v);
			}
		}else{
			if(get_magic_quotes_gpc()){
				$data = trim(stripslashes($data));
			}
			$data = strip_tags($data);
			$clean_input = trim($data);
		}
		return $clean_input;
	}	

	public function is($type) {
		return strtolower($this->getMethod()) == strtolower($type);
	}

	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	public function getReferer(){
		return $_SERVER['HTTP_REFERER'];
	}

}



?>