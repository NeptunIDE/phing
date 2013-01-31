<?php

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