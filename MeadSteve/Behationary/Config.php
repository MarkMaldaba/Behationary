<?php

namespace MeadSteve\Behationary;

class Config {

	protected $_configFile;

	protected $_contexts;

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

}