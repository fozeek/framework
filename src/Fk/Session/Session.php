<?php

namespace Fk\Session;

/*
  Class :		SessionComponent
  Lien : 		site/library/components/session.class.php
  Déscription :
  Permet de gérer les données de session
 */

class Session {
    /*
      Constructeur
     */

    public function __construct($controller) {
	   //parent::__construct($controller);
    }

    /*
      write
      Enegistrer une donnée en session
     */

    public function write($key, $value) {
	$_SESSION[$key] = $value;
    }

    /*
      read
      Lire une donnée en session
     */

    public function read($key) {
	return (array_key_exists($key, $_SESSION)) ?
		$_SESSION[$key] :
		false;
    }

    /*
      read
      Lire une donnée en session
     */

    public function clear($key) {
	unset($_SESSION[$key]);
    }

    /*
      containsKey
      Retourne vrai si une clé existe en session
     */

    public function containsKey($key) {
	return (isset($_SESSION[$key]));
    }

}

?>