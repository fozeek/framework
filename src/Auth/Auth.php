<?php

namespace Core\Component\Auth;

use Core\Resource\Component;

class Auth extends Component {

    private $_user = null;

    public function __construct($controller, $params = null) {
	parent::__construct($controller, $params);
    }

    public function __transmit($params) {
	return array("user" => $this->getUser());
    }

    public function connect($pseudo, $pwd) {
		$user = $this->_controller->Model->User->getByPseudo($pseudo);
		if($user) {
			if ($user->get("password") == md5($pwd)) {
			    $this->_controller->Session->write("Auth", $user->get("id"));
			    return $user;
			}
			else
			    return false;
		}
    }

    public function disconnect() {
	$this->_controller->Session->clear("Auth");
    }

    public function getUser() {
		return ($this->_controller->Session->read("Auth")) ?
			$this->_controller->Model->User->getById($this->_controller->Session->read("Auth")) :
			false;
    }

    public function setFirstConnection() {
	$this->_controller->Session->write("first_connection", true);
    }

    public function getFirstConnection() {
	$this->_controller->Session->read("first_connection");
    }

    public function persist() {
	// fait une connexion persistant en cookie (cf. tuto grafikart)
    }

}