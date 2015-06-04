<?php

namespace MeadSteve\Behationary;

class Config {

	protected static $_singletonInstance;
	protected static $_singletonConfigFile;
	protected static $_selectedProject = "";

	protected $_configFile;

	protected $_contexts;
	protected $_behatRootPath = "";
	protected $_projectName = "Default Project";

/////////////////////////////////////////////////////////////
// FACTORY FUNCTIONS/SINGLETON CONFIGURATION

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

/////////////////////////////////////////////////////////////
// PROJECTS

/**
 * getProjects()
 * Returns an array containing all projects defined on the system.
 * The key is the internal project identifier, and the value is the display name.
 * Currently, there is either zero or one project defined, but I plan to add support
 * for multiple projects soon.
 */
	public static function getProjects()
	{
		$config = self::get();

		if ($config->isValid()) {
			return array('default' => $config->getProjectName());
		}
		else {
			return array();
		}
	}

	public static function projectExists($projectId)
	{
		$projectId = strtolower($projectId);
		$arrProjects = self::get()->getProjects();

		if (isset($arrProjects[$projectId])) {
			return true;
		}
		else {
			return false;
		}
	}

	public static function selectProject($projectId)
	{
		if (!self::projectExists($projectId)) {
			$errorMsg = "'" . $projectId . "' is not a valid project";
            throw new \InvalidArgumentException($errorMsg);
		}

		self::$_selectedProject = strtolower($projectId);
	}

	public static function getSelectedProject()
	{
		// If no project is selected, return the first key in the projects array.
		// If no projects are defined, and empty string is returned.
		if (self::$_selectedProject == "") {
			$arrProjects = self::getProjects();
			if (count($arrProjects) > 0) {
				reset($arrProjects);
				return key($arrProjects);
			}
			else {
				return "";
			}
		}
		// Otherwise return the selected project.
		else {
			return self::$_selectedProject;
		}
	}

/////////////////////////////////////////////////////////////
// CONSTRUCTOR

    public function __construct($configFile = "")
    {
		$this->_configFile = $configFile;
		$this->_loadConfigFile();
    }

/////////////////////////////////////////////////////////////
// CONFIG FILE READING/PARSING

	public function isValid()
	{
		if ($this->_configFile != "" && $this->_configFile != null
			&& is_file($this->_configFile) && is_readable($this->_configFile))
		{
			return true;
		}
		else {
			return false;
		}
	}

	protected function _loadConfigFile()
	{
		if ($this->isValid()) {
			require_once($this->_configFile);

			// Root path to the Behat install folder.  This will be used as the
			// path for the autoloader and for 
			if (isset($behatRootPath)) {
				$this->_behatRootPath = (string) $behatRootPath;
			}

			// Project name is optionally, but may be set in the config file, in
			// which case it will be displayed in the UI.
			if (isset($projectName) && trim($projectName) != "") {
				$this->_projectName = trim($projectName);
			}
		}
	}

/////////////////////////////////////////////////////////////
// CONFIGURATION SETTINGS

	public function getBehatRootPath()
	{
		return $this->_behatRootPath;
	}

	public function getProjectName()
	{
		return $this->_projectName;
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