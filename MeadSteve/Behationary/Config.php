<?php

namespace MeadSteve\Behationary;

class Config {

	protected static $_singletonInstance;
	protected static $_singletonConfigFile;

	protected $_configFile;

	protected $_contexts;
	protected $_behatRootPath = "";

	public static function setPath($configFile) {
		if ($configFile != self::$_singletonConfigFile) {
			self::$_singletonConfigFile = $configFile;
			self::$_singletonInstance = null;
		}
	}

	public static function getPath() {
		return self::$_singletonConfigFile;
	}

	public static function get() {
		if (!isset(self::$_singletonInstance)) {
			self::$_singletonInstance = new self(self::getPath());
		}

		return self::$_singletonInstance;
	}

    public function __construct($configFile = "")
    {
		$this->_configFile = $configFile;
		$this->_loadConfigFile();
    }

	protected function _loadConfigFile()
	{
		if ($this->_configFile != "" && $this->_configFile != null
			&& is_file($this->_configFile) && is_readable($this->_configFile))
		{
			require_once($this->_configFile);

			// Root path to the Behat install folder.  This will be used as the
			// path for the autoloader and for 
			if (isset($behatRootPath)) {
				$this->_behatRootPath = (string) $behatRootPath;
			}
		}
	}

	public function getContexts()
	{
		if (!is_array($this->_contexts)) {
			if (function_exists('\Behationary\getContexts')) {
				$this->_contexts = \Behationary\getContexts();
			}

			if (!is_array($this->_contexts))
				$this->_contexts = array();
		}

		return $this->_contexts;
	}

	public function getBehatRootPath()
	{
		return $this->_behatRootPath;
	}

}