<?php

namespace Fk\Route;

use Fk\Uri\Uri;
use Fk\Config\Config;

class Router {

	public static $routes = array();
	public static $defaultController = "home";
	public static $defaultAction = "index";
	public static $regex = "A-Za-z0-9\-";
	public static $namespaces;

	public static function addNamespace($namespace, $realNamespace) {
		self::$namespaces[$namespace] = $realNamespace;
	}

	public static function getNamespaces() {
		return self::$namespaces;
	}

	public static function getNamespace($namespace) {
		return self::$namespaces[$namespace];
	}

	public static function setDefaultsRoutes($defaultController, $defaultAction) {
		self::$defaultController = $defaultController;
		self::$defaultAction = $defaultAction;
	}

	public static function setRegex($regex) {
		self::$regex = $regex;
	}

	public static function addModuleRouteConfig($routeConfig) {
		if(array_key_exists('namespaces', $routeConfig)) {
			foreach ($routeConfig['namespaces'] as $key => $namespace) {
				self::addNamespace($key, $namespace['namespace']);
			}
		}
	}

	// public static function getRoutes($key = null, $lang = null) {
	// 	if($lang === null)
	// 		$lang = static::$_kernel->getCurrentLang();
	// 	return ($key === null) ?
	// 		((isset(self::$routes[$lang])) ? self::$routes[$lang] : array() ) : 
	// 		((isset(self::$routes[$lang][$key])) ? self::$routes[$lang][$key] : false );
	// }

	public static function addRoute($lang, $namespace, $controller, $action, $pattern, array $options = array()) {
		if(!isset(self::$routes[$lang]))
			self::$routes[$lang] = array();
		array_push(self::$routes[$lang], array("controller" => $controller, "action" => $action, "pattern" => $pattern));
	}

	// public function loadPaths($paths) {
 //        foreach ($paths as $key => $value) {
 //            $pathTmp = array_values(array_filter(explode("%", preg_replace("#{([".Router::$_regex."]+)}#i", "%*$1*%", $value))));
 //            foreach ($pathTmp as $key2 => $value2)
 //                if(preg_match("#\*([".Router::$_regex."]+)\*#i", $value2))
 //                    $pathTmp[$key2] = $paths[str_replace("*", "", $value2)];
 //            $paths[$key] = implode("", $pathTmp);
 //        }
 //        $this->_paths = $paths;
 //    }

 //    public function path($name, $absolute = false) {
 //        return (isset($this->_paths[$name])) ? ($absolute) ? "/" : "".$this->_paths[$name] : false ;
 //    }

	// public static function findRoute($controller, $action, $lang) {
	// 	foreach (self::getRoutes(null, $lang) as $key => $value) {
	// 		if($value["controller"] == $controller && $value["action"] == $action)
	// 			return self::getRoutes($key, $lang);
	// 	}
	// 	return false;
	// }

	// public static function findPattern($pattern, $method = false) { // method is for anonymous params
	// 	if(!$method) {
	// 		foreach (self::getRoutes() as $key => $value) {
	// 			if($value["pattern"] == $pattern)
	// 				return self::getRoutes($key);
	// 		}
	// 	}
	// 	else {
	// 		foreach (self::getRoutes() as $key => $value) {
	// 			$regex = "#".preg_replace("#{([".self::$_regex."]+)}#i", "([".self::$_regex."]+)", $value["pattern"])."#i";
				
	// 			if(preg_match($regex, $pattern))
	// 				return self::getRoutes($key);
	// 		}
	// 	}
	// 	return false;
	// }

	// public static function getUrl($controller, $action, $params = null, $lang = null) {
	// 	if($lang===null)
	// 		$lang = static::$_kernel->getCurrentLang();
	// 	if($route = self::findRoute($controller, $action, $lang)) {
	// 		$url = $route["pattern"];
	// 		if($params!==null)	
	// 			foreach ($params as $key => $value)
	// 				$url = str_replace("{".$key."}", $value, $url);
	// 		return "/".$lang.$url;
	// 	}
	// 	else {

	// 		$url = "/".$controller."/".$action;
	// 		if($params!==null)
	// 			foreach ($params as $value)
	// 				$url .= "/".$value;
	// 		return "/".$lang.$url;
	// 	}
	// }

	public static function getUrl($controller, $action, $params = array(), $lang = null, $namespace = null) {
		return '/'.((empty($namespace)) ? 'default' : $namespace )
			.'/'.((empty($lang)) ? Config::read('langs.default') : $lang )
			.'/'.$controller
			.'/'.$action
			.'/'.implode('/', $params);
	}

	public static function getRewrittenPath($url) {
		return array_values(array_filter(explode("/", $url)));
	}

	public static function getRoute(Uri $uri) {
		$route = new Route();
		$path = self::getRewrittenPath($uri->getPathString());

		// Set the namespace
		if(count($path)>0) {
			if(array_key_exists($path[0], self::getNamespaces())) {
				$namespace = self::getNamespace($path[0]);
				$namespaceUrl = $path[0].'/';
				$path = array_slice($path, 1);
			}
			else {
				$namespace = self::getNamespace('_default');
				$namespaceUrl = '';
			}
		}
		else {
			header('Location:/'.Config::read('langs.default').'/');
		}
		$route->setModuleName(current(explode('\\', ltrim($namespace, '\\')))); // Cause the maduleName is the first of all namespaces

		// Set the lang
		$langs = Config::read('langs');
		if(count($path) > 0 ) {
			if(in_array($path[0], $langs['autorized'])) {
				$route->setLang($path[0]);
				$path = array_values(array_slice($path, 1));
			}
			else {
				header('Location:/'.$namespaceUrl.$langs['default']);//.'/'.implode('/', array_slice($path, 1)));
			}
		}
		else {
			header('Location:/'.$namespaceUrl.$langs['default']);//.'/'.implode('/', $path));
		}

		// Set the controller name
		if(count($path)>0) {
			$controllerName = $namespace.'\\'.ucfirst(strtolower($path[0]));
			$path = array_values(array_slice($path, 1));
		}
		else {
			$controllerName = $namespace.'\\'.self::$defaultController;
		}
		$route->setControllerName($controllerName);

		// Set the action name
		if(count($path)>0)
			$route->setActionName(strtolower($path[0]).'Action');
		else
			$route->setActionName(self::$defaultAction.'Action');

		// Set parameters
		$route->setParams(array_slice($path, 1));

		return $route;



		// $pattern = explode("?", $pattern);
		// $pattern = $pattern[0];
		
		// if($route = self::findPattern(preg_replace("#{([".self::$_regex."]+)}#i", "{}", $pattern), true)) {
		// 	$tab = preg_split("#[{}]#i", $route["pattern"]);
		// 	if(count($tab) > 1) {
		// 		foreach ($tab as $key => $value) {
		// 			if($key%2 == 1)
		// 				$paramsName[] = $value;
		// 			else
		// 				$pattern = str_replace($value, "%", $pattern);
		// 		}
		// 		$paramsValue = explode("%", $pattern);
		// 		foreach ($paramsName as $key => $value)
		// 			$params[$value] = $paramsValue[$key+1];
		// 	}
		// 	else
		// 		$params = array();
		// 	return array("controller" => $route["controller"], "action" => $route["action"], "params" => $params);
		// }
		// else {
		// 	$route = array_values(array_filter(explode("/", $pattern)));
		// 	return array(
		// 			"controller" => (array_key_exists(0, $route)) ? $route[0] : self::$_defaultController,
		// 			"action" => (array_key_exists(1, $route)) ? $route[1] : self::$_defaultAction,
		// 			"params" => array_slice($route, 2),
		// 		);
		// }
	}


}