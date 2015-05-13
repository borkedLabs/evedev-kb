<?php

class API {
	private $error = null;

	protected $pheal = null;
	
	function API() {
		// Loads pheal, so we can do some stuff with it, 
		
		// init API connection method
		API_Helpers::autoSetApiConnectionMethod();
		if(config::get('apiConnectionMethod') == 'curl')
		{
			\Pheal\Core\Config::getInstance()->http_method = 'curl';
		}
		else
		{
			\Pheal\Core\Config::getInstance()->http_method = 'file';
		}
		
		if(!defined(KB_CACHEDIR))
		{
			define(KB_CACHEDIR, 'cache');
		}
		\Pheal\Core\Config::getInstance()->http_post = false;
		\Pheal\Core\Config::getInstance()->http_keepalive = true;
		// default 15 seconds
		\Pheal\Core\Config::getInstance()->http_keepalive = 10; 
		// KeepAliveTimeout in seconds
		\Pheal\Core\Config::getInstance()->http_timeout = 60;
		
		\Pheal\Core\Config::getInstance()->cache = new \Pheal\Cache\FileStorage(KB_CACHEDIR.'/api/', array('delimiter' => '-'));
		\Pheal\Core\Config::getInstance()->api_customkeys = true;
		\Pheal\Core\Config::getInstance()->log = new \Pheal\Log\FileStorage(KB_CACHEDIR.'/api/');
	}

	function IsCached() {
		$isCached = (bool)Pheal\Core\Config::getInstance()->cache->load($keyID, $vCode, $this->options['scope'], $this->options['name'], $api['args']); 
	}
	
	function CallAPI( $scope, $call, $data, $userid, $key ) {
		//echo $scope, $call, $userid, $key;
		//PhealConfig::getInstance()->api_customkeys = false;
		$this->pheal = new \Pheal\Pheal($userid, $key, $scope);
		$this->error = null;
		
		try {
			if( is_array( $data ) ) {
				$result = $this->pheal->{$call}($data);
			} else {
				$result = $this->pheal->{$call}();
			}
		} catch(\Pheal\Exceptions\PhealException $e) {
			$this->error = $e->getCode();
			$this->message = $e->getMessage();
			return false;
		}
		
		return $result;
	}

	/**
	* Return any error codes encountered or null if none.
	 *
	 * @return integer
	 */
	function getError()
	{
		return $this->error;
	}
	/**
	* Return any error messages encountered or null if none.
	 *
	 * @return string
	 */
	function getMessage()
	{
		return $this->message;
	}
}