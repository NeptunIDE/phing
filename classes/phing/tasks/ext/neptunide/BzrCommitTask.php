<?php

require_once 'phing/Task.php';
require_once 'phing/tasks/ext/neptunide/BzrTask.php';

class BzrCommitTask extends BzrTask
{
	protected $message;
	
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function init()
    {
        return true;
    }

    public function main()
    {
		$options = array();
		
		$diffCmd = $this->createCommand("status","", array("-S"));
		
		$this->log("Veryfing if there is something to commit");
		
		$this->executeCommand($diffCmd, true, $output);
		
		
		if (empty($output))
		{
			$this->log("bzr status returned empty output. Assuming there is nothing to commit, aborting.");
			
			return;
		}
		else
		{
			$foundSomething = false;
			
			foreach($output as $line)
			{
				if ($line[0] !== "?")
				{
					$foundSomething = true;
				}
			}
			
			if (!$foundSomething)
			{
				$this->log("bzr status returned non empty output, but all files are unversioned. Assuming there is nothing to commit, aborting.");
				
				return;
			}
		}
		
		$this->log("Changes found, proceeding with commit");
		
        if (!empty($this->message))
        {
            $options['-m'] = $this->message;
        }
		else
		{
			throw new BuildException("Commit failed: empty commit message");
		}

        $cmd = $this->createCommand("commit", "", $options);

        $this->executeCommand($cmd, true);
    }
}