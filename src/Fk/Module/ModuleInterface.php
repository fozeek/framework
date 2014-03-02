<?php

namespace Fk\Module;

interface ModuleInterface {

	public function getModuleConfig();
	public function getAutoLoadConfig();
	public function getListenerConfig();
	public function getRouteConfig();

}