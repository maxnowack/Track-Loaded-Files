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
 
require("TrackLoadedFiles.class.php");
$plf = new TrackLoadedFiles("openFile.php?file=","./");
?>
<html>
    <head>
        <title>TrackLoadedFiles FormExample</title>
        <link href="<?php echo $plf->showLinkTo("main.css"); /* the function "showLinkTo()" is required to make the script working */ ?>" rel="stylesheet" type="text/css" />
    </head>
    <body>
    Test test test<br />
    <?php
        if(isset($_POST['textfield']) && $plf->track()) // the function "track()" track if the files are loaded
        {
            echo $_POST['textfield'];
        }
        else
        {
            echo "<img src=\"".$plf->showLinkTo("testimg.png")."\" alt=\"Test Image\" /><br />";
            echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">";
            echo "<input type=\"text\" name=\"textfield\" />";
            echo "<input type=\"submit\" value=\"Send\" />";
            echo "</form>";
        }
    ?>
    </body>
</html>
