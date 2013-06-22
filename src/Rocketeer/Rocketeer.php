<?php
namespace Rocketeer;

use Illuminate\Config\Repository;
use Illuminate\Support\Str;

class Rocketeer
{

	/**
	 * The Config Repository
	 *
	 * @var Repository
	 */
	protected $config;

	/**
	 * The Rocketeer version
	 *
	 * @var string
	 */
	public static $version = '0.1.0';

	/**
	 * Build a new ReleasesManager
	 *
	 * @param Repository $config
	 */
	public function __construct(Repository $config)
	{
		$this->config = $config;
	}

	////////////////////////////////////////////////////////////////////
	///////////////////////////// APPLICATION //////////////////////////
	////////////////////////////////////////////////////////////////////

	/**
	 * Get the name of the application to deploy
	 *
	 * @return string
	 */
	public function getApplicationName()
	{
		return $this->config->get('rocketeer::remote.application_name');
	}

	/**
	 * Get the Git repository
	 *
	 * @return string
	 */
	public function getGitRepository()
	{
		// Get credentials
		$repository = $this->config->get('rocketeer::git');
		$username   = $repository['username'];
		$password   = $repository['password'];
		$repository = $repository['repository'];

		// Add credentials if HTTPS
		if (str_contains($repository, 'https://'.$username)) {
			$repository = str_replace($username.'@', $username.':'.$password.'@', $repository);
		} else {
			$repository = str_replace('https://', 'https://'.$username.':'.$password.'@', $repository);
		}

		return $repository;
	}

	////////////////////////////////////////////////////////////////////
	//////////////////////////////// PATHS /////////////////////////////
	////////////////////////////////////////////////////////////////////

	/**
	 * Get the path to a folder
	 *
	 * @param  strng $folder
	 *
	 * @return string
	 */
	public function getFolder($folder = null)
	{
		$base   = $this->getHomeFolder().'/';
		$folder = str_replace($base, null, $folder);

		return $base.$folder;
	}

	/**
	 * Get the path to the remote folder
	 *
	 * @return string
	 */
	public function getHomeFolder()
	{
		$rootDirectory = $this->config->get('rocketeer::remote.root_directory');
		$rootDirectory = Str::finish($rootDirectory, '/');

		return $rootDirectory.$this->getApplicationName();
	}

}