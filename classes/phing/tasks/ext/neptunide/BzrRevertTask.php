<?php

require_once 'phing/Task.php';
require_once 'phing/tasks/ext/neptunide/BzrTask.php';

class BzrRevertTask extends BzrTask
{
	protected $file = null;
	
	protected $filesets = array();
	
	public function setFile(PhingFile $file)
    {
        $this->file = $file;
    }
	
	/**
     *  Nested creator, adds a set of files (nested fileset attribute).
     */
    public function createFileSet()
    {
        $num = array_push($this->filesets, new FileSet());
        return $this->filesets[$num - 1];
    }
	
	public function init()
    {
        return true;
    }

    public function main()
    {
		$options = array();
		
        $filesToParse = array();

        if ($this->file instanceof PhingFile) {
            $filesToParse[] = $this->file->getPath();
        } else {
            // append any files in filesets
            foreach ($this->filesets as $fs) {
                $files = $fs->getDirectoryScanner($this->project)->getIncludedFiles();
                foreach ($files as $filename) {
                     $f = new PhingFile($fs->getDir($this->project), $filename);
                     $filesToParse[] = $f->getAbsolutePath();
                }
            }
        }

        $cmd = $this->createCommand("revert", $filesToParse, $options);

        $this->executeCommand($cmd, true);
    }
}