<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of directoryParser
 *
 * @author BlakeMesdag
 */
class DirectoryParser {
    public $currentDir, $dirContents, $numElements;

    public function __construct()
    {
        global $baseDir;

        $target = $_GET['targetDir'];

        //Sanitize to prevent back-pedalling directories
        $target = rtrim($target, "/") . "/";
        $target = str_replace("../", "/", $target);

        $this->currentDir = !isset($target) ? $baseDir: $baseDir . $target;
        $this->numElements = 0;
        $this->loadDirContents();
        $this->printDirContents();
    }

    public function loadDirContents()
    {
        $tempDirContents = scandir($this->currentDir);

        foreach($tempDirContents as $temp)
        {
            if($temp != "." && $temp != "..")
            {
                clearstatcache();
                if(time() - filemtime($this->currentDir . $temp) > 120 || is_dir($this->currentDir . $temp))
                {
                    $this->processReadFile($temp, $this->numElements++);
                }
            }
        }
    }

    public function processReadFile($file, $index)
    {
        $this->dirContents[$index] = array( "filetype" => "", "name" => "", "size" => "" );

        $temp = explode(".", $file);

        $isDir = is_dir($this->currentDir . $file);

        $this->dirContents[$index]["filetype"] = !$isDir ? $this->filetype($temp[count($temp) - 1]) : "directory";
        $this->dirContents[$index]["name"] = implode( ".", $temp );
        $this->dirContents[$index]["size"] = filesize($this->currentDir . $file);
    }

    public function filetype($extension)
    {
        global $extensions;

        foreach ( $extensions as $type => $ext )
        {
            foreach( $ext as $value )
            {
                if( $extension == $value )
                {
                    return $type;
                }
            }
        }
    }

    public function printDirContents()
    {
        print_r(json_encode($this->dirContents));
    }
}
?>
