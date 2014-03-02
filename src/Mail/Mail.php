<?php

namespace Core\Component\Mail;

use Core\Resource\Component;

class Mail extends Component {

    private $_from;
    private $_fromName;
    private $_subject;
    private $_to;
    private $_header;
    private $_text;
    private $_carriage_return;

    public function __construct($params = null) {
    	if ($params !== null && is_array($params)) {
    	    if (array_key_exists("from", $params))
    		from($params["from"]);
    	    elseif (array_key_exists("to", $params))
    		to($params["to"]);
    	    elseif (array_key_exists("header", $params))
    		header($params["header"]);
    	    elseif (array_key_exists("text", $params))
    		text($params["text"]);
    	    elseif (array_key_exists("subject", $params))
    		subject($params["subject"]);
    	}
    	return $this;
    }

    public function from($from) {
    	$this->_from = $from;
    	return $this;
    }

    public function to($to) {
    	$this->_to = $to;
    	return $this;
    }
    
    public function subject($subject) {
    	$this->_subject = $subject;
    	return $this;
    }
    
    public function fromName($pseudo) {
    	$this->_fromName = $pseudo;
    	return $this;
    }

    public function header($header, $value) {
    	$this->setCarriageReturn();
    	if ($header == "from")
    	    $this->_headers = "From: \"" . $value["name"] . "\"" . $value["mail"] . $this->_carriage_return;
    	return $this;
    }

    public function buildHeaders() {
        $this->headers = 'MIME-Version: 1.0' . $this->_carriage_return;
        $this->headers .= 'Content-type: text/html; charset=UTF-8' . $this->_carriage_return;

        $this->headers .= 'To: ' . $this->_to . $this->_carriage_return;
        $this->headers .= 'From: WEBTUTS, Personne : ' . $this->_fromName . ' <contact@webtuts.fr>' . $this->_carriage_return;
		
        return $this;
    }

    public function text($text) {
        $this->_text = $text;
    	return $this;
    }


    private function setCarriageReturn() {
    	if (!empty($this->_to)) {
    	    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $this->_to))
    		$this->_carriage_return = "\r\n";
    	    else
    		$this->_carriage_return = "\n";
    	}
    }

    public function send() {
	   mail($this->_to, $this->_subject, $this->_text, $this->_header);
    }

}

?>