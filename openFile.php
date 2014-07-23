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

if(isset($_GET['file']))
{
    require("TrackLoadedFiles.class.php");
    $plf = new TrackLoadedFiles("openFile.php?file=","./");

    //send the mimetype of the file to the browser
    header("Content-Type: ".$plf->getMimeType($_GET['file']));

    //send the content of the file to the browser
    echo $plf->load($_GET['file']);
}
?> 
