<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jakub Laskowski
 * Date: 30.01.13
 * Time: 17:40
 * To change this template use File | Settings | File Templates.
 */

require_once 'phing/Task.php';

abstract class BzrTask extends Task
{
    protected $bzrPath = 'bzr';

    protected $username;
    protected $password;

    protected $caBundle;
    protected $clientBundle; /* This is only supported by special NeptunIDE hacked version of Bazzar */
	
	protected $autoFail = true;

    public function setBzrPath($bzrPath)
    {
        $this->bzrPath = $bzrPath;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setCaBundle($caBundle)
    {
        $this->caBundle = $caBundle;
    }

    public function setClientBundle($clientBundle)
    {
        $this->clientBundle = $clientBundle;
    }
	
	public function setAutoFail($af)
	{
		$this->autoFail = (bool)$af;
	}

    protected function createCommand($bzrCommand, $argument = "", $options = array())
    {
        $args = array();
		
		if (is_array($argument))
		{
			$argument = join(" ", $argument);
		}

        foreach ($options as $key => $option)
        {
            if (is_numeric($key))
            {
                $args[] = escapeshellarg($option);
            }
            else
            {
                $args[] = escapeshellarg($key).' '.escapeshellarg($option);
            }
        }

        $cmd = $this->bzrPath.' '.$bzrCommand.' '.$argument.' '.join(' ', $args)." 2>&1";

        return $cmd;
    }

    protected function executeCommand($command, $includeBundles = false, &$output = null)
    {
		if ($includeBundles)
		{
			putenv('CURL_CA_BUNDLE='.$this->caBundle);
			putenv('CURL_CLIENT_BUNDLE='.$this->clientBundle);
		}

		Phing::exec($command, $output, $return);
		
		if (is_array($output))
		{
			foreach ($output as $line)
			{
				$this->log($line);
			}
		}
		
		if ($return !== 0 && $this->autoFail)
		{
			throw new BuildException("Error in running bzr $command, bzr returned ".$return);
		}

		return $return;
    }
}
