 <?php
//simpilotgroup addon module for phpVMS virtual airline system
//
//simpilotgroup addon modules are licenced under the following license:
//Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
//To view full icense text visit http://creativecommons.org/licenses/by-nc-sa/3.0/
//
//@author David Clark (simpilot)
//@copyright Copyright (c) 2009-2010, David Clark
//@license http://creativecommons.org/licenses/by-nc-sa/3.0/

 
$pagination = new Pagination();
$pagination->setLink("screenshots?page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total);
$screenshots = ScreenshotsData::getpagnated($pagination->getLimitSql());
?>

<table width="100%">
        <tr>
            <td width="50%"><h4>Screenshot Gallery</h4></td>
            <td width="50%" align="right">
                <?php
                if(Auth::LoggedIn())
                    {
                        if(PilotGroups::group_has_perm(Auth::$usergroups, ACCESS_ADMIN))
                        {
                            echo '<form method="link" action="'.SITE_URL.'/index.php/screenshots/approval_list">
                                <input class="mail" type="submit" value="Screenshot Approval List"></form><br />';
                        }
                        echo '<form method="link" action="'.SITE_URL.'/index.php/screenshots/upload">
                        <input class="mail" type="submit" value="Upload A New Screenshot"></form></td>';
                     }
                     else
                     {
                         echo 'Login to rate or upload screenshots.';
                     }
                     ?>
        </tr>
    </table>
    <hr />
    <center>
        <b>Click on any image to view fullsize.</b><br /><br />
<?php
if (!$screenshots) {echo '<div id="error">There are no screenshots in the database!</div>'; }
else {
    echo '<table class="profiletop">';
    $tiles=0;
    foreach($screenshots as $screenshot) {
        $pilot = PilotData::getpilotdata($screenshot->pilot_id);
        if(!$screenshot->file_description)
        {$screenshot->file_description = 'Not Available';}
        if ($tiles == '0') { echo '<tr>'; }
        echo '<td width="25%" valign="top"><br />
                    Views: '.$screenshot->views.' - Rating: '.$screenshot->rating.'<br /><br />
                    <a href="'.SITE_URL.'/index.php/Screenshots/large_screenshot?id='.$screenshot->id.'">
                        <img src="'.SITE_URL.'/pics/'.$screenshot->file_name.'" border="0" width="200px" height="150px" alt="By: '.$pilot->firstname.' '.$pilot->lastname.'" /></a>
                            <br />
                    <u>Submited By:</u> '.$pilot->firstname.' '.$pilot->lastname.' - '.PilotData::getpilotcode($pilot->code, $pilot->pilotid).'<br />
                    <u>Date:</u> '.date('m/d/Y', strtotime($screenshot->date_uploaded)).'<br />
                    <u>Description:</u> '.$screenshot->file_description.'<br /><br />
                </td>';
        $tiles++;
        if ($tiles == '4') {  echo '</tr>'; $tiles=0; }
    }
    echo '</table>';
}
$navigation = $pagination->create_links();
echo $navigation;
?>
    </center>