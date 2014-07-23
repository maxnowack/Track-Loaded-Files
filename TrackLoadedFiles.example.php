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

require("TrackLoadedFiles.class.php"); //load the class

//create the class-object
//1. parameter: link to the file which open the images or stylesheets (path to image-file will be attached)
//2. parameter: directory of the images or stylesheets
$plf = new TrackLoadedFiles("TrackLoadedFiles.example.php?file=","");

if(isset($_GET['file']))
{
    //send the file to the browser
    header("Content-Type: ".$plf->getMimeType($_GET['file']));
    echo $plf->load($_GET['file']);
}
elseif(isset($_GET['track']))
{
    //track loaded files
    if($plf->track())
    {
        echo "success";
    }
    else
    {
        echo "failed";
    }
}
elseif(isset($_GET['destroy']))
{
    //destroy the session (just for test)
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
}
else
{
    //show the image and the link to the track-script
    echo "<img src='".$plf->showLinkTo("test.png")."' /><br />";
    echo "<a href='".$_SERVER['PHP_SELF']."?track'>Show Result</a>";
}
?> 
