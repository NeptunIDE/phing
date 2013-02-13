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
		
		$diffCmd = $this->createCommand("diff");
		
		$this->executeCommand($diffCmd, true, $output);
		
		if (empty($output))
		{
			$this->log("Diff returnet empty output. Assuming there is nothing to commit, aborting.");
			
			return;
		}
		
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