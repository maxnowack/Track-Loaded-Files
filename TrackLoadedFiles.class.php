 <?php
/*******************************************
 * Author: Max Nowack                      *
 * Website: www.dasnov.de                  *
 * Classname: TrackLoadedFiles             *
 *******************************************
 * Description:                            *
 * The class track which files are loaded  *
 * from the client. With this class your   *
 * can make forms or sites securer.        *
 * Example:                                *
 * The client must load all external files *
 * they liked in the script (stylesheets,  *
 * images, etc.). Otherwise the next site  *
 * will not open.                          *
 *******************************************/

session_start();    //Start the Session. If you also use sessions on your script, comment this line out.


define("SESSION_VAR_PREFIX",    "TrackLoadedFiles_");
    
class TrackLoadedFiles
{
    var $linkTo;        //link to the file that open the other files (images, stylesheets, etc.)
    var $pathTo;        //path to the other files (images, stylesheets, etc.)
    var $loadedFiles;    //array that contains the files opened by the script
    var $showedLinks;    //array that contains the files showed by the script
    var $numSitesInSession;    //number of the runtimes of the class in this session

    function __construct($linkTo,$pathTo)
    {
        //make the sessionvars local
        $this->loadedFiles = $this->getSessionVar("loadedFiles",array());
        $this->showedLinks = $this->getSessionVar("showedLinks",array());
        $this->numSitesInSession = $this->getSessionVar("numSitesInSession",0);

        //save the classparameters
        $this->linkTo = $linkTo;
        $this->pathTo = $pathTo;

        $this->numSitesInSession++;    //increase session site number
    }

    function __destruct()
    {
        //save the localvars into sessionvars
        $this->setSessionVar("loadedFiles",$this->loadedFiles);
        $this->setSessionVar("showedLinks",$this->showedLinks);
        $this->setSessionVar("numSitesInSession",$this->numSitesInSession);
    }

    function showLinkTo($file)
    {
    //this function returns the link to the file $file
        $this->showedLinks[$this->numSitesInSession][] = $file;
        if(strstr($this->linkTo,"?"))
        {
            return $this->linkTo.$file."&x=".time();            
        }
        else
        {
            return $this->linkTo.$file."?x=".time();
        }
    }

    function getMimeType($file)
    {
    //this function returns the mimetype of the file $file
        if(function_exists(finfo_open))    //tests finfo installation
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);    // return mime type ala mimetype extension
            return finfo_file($finfo,$this->pathTo.$file);
        }
        else    //use the old function
        {
            return mime_content_type($this->pathTo.$file);
        }
    }

    function load($file)
    {
    //this function returns the content of the file $file
    //if the link to the file was never shown in this session or , the function will return false
        $this->numSitesInSession--;    //decrease session site number
        if($this->canLoadFile($file) && file_exists($this->pathTo.$file))
        {
            $this->loadedFiles[$this->numSitesInSession][] = $file; //add the file to the loadedFiles-Array
            return file_get_contents($this->pathTo.$file); // return the file content
        }
        else
        {
            return false;
        }
    }

    function track()
    {
    //this function track the loaded files. If all files are loaded,
    //the function will return true. Otherwise the function return false
        if($this->numSitesInSession>1) //start just in sessions > 1
        {

            $lastSession = $this->numSitesInSession - 1; //get the last session
            
            //track showed links and loaded files > 0
            if(count($this->loadedFiles[$lastSession])>0 && count($this->showedLinks[$lastSession])>0)
            {
                //sort the arrays
                sort($this->loadedFiles[$lastSession]);
                sort($this->showedLinks[$lastSession]);

                if($this->loadedFiles[$lastSession]==$this->showedLinks[$lastSession])
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            elseif(count($this->loadedFiles[$lastSession])>0 xor count($this->showedLinks[$lastSession])>0)
            {
                return false;
            }
            else
            {
                return true;
            }

        }
        else
        {
            return true;
        }
    }

    function canLoadFile($file)
    {//this function return true if the file was allready shown
        $arr_str = json_encode($this->showedLinks);
        if(strstr($arr_str,$file))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function getSessionVar($var,$init)
    {
        if(!isset($_SESSION[SESSION_VAR_PREFIX.$var]))
        {
            $this->setSessionVar($var,$init);
        }

        return $_SESSION[SESSION_VAR_PREFIX.$var];
    }

    function setSessionVar($var,$val)
    {
        $_SESSION[SESSION_VAR_PREFIX.$var] = $val;
    }
}
?> 
