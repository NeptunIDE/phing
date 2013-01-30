<?php

require_once 'phing/Task.php';
require_once 'phing/tasks/ext/neptunide/BzrTask.php';

class BzrMergeTask extends BzrTask
{
    protected $mergeFrom;
    protected $mergeRevision;

    public function setMergeFrom($mergeFrom)
    {
        $this->mergeFrom = $mergeFrom;
    }

    public function setMergeRevision($mergeRevision)
    {
        $this->mergeRevision = $mergeRevision;
    }


    public function init()
    {
        return true;
    }

    public function main()
    {
        if (!empty($this->mergeRevision))
        {
            $options['-r'] = $this->mergeRevision;
        }

        $cmd = $this->createCommand("merge", $this->mergeFrom, $options);

        $result = $this->executeCommand($cmd, true);

        if ($result  !== 0)
        {
            throw new BuildException("Error in running bzr merge, bzr returned ".$result);
        }

    }

}