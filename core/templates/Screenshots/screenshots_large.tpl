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

$pilot = PilotData::getPilotData($screenshot->pilot_id);
?>
<table width="100%">
        <tr>
            <td>
                <?php
                    $previous = ScreenshotsData::get_previous($screenshot->id);
                    if(!$previous)
                    {echo '&nbsp'; }
                    else
                    {
                ?>
                <form method="post" action="<?php echo SITE_URL ?>/index.php/Screenshots" >
                <input type="hidden" name="action" value="last" />
                <input type="hidden" name="id" value="<?php echo $previous->id; ?>" />
                <input class="mail" type="submit" value="Previous Screenshot">
                </form>
                <?php
                    }
                    ?>
            </td>
            <td colspan="2" align="right">
                <?php
                    $next = ScreenshotsData::get_next($screenshot->id);
                    if(!$next)
                    {echo '&nbsp'; }
                    else
                    {
                ?>
                <form method="post" action="<?php echo SITE_URL ?>/index.php/Screenshots" >
                <input type="hidden" name="action" value="last" />
                <input type="hidden" name="id" value="<?php echo $next->id; ?>" />
                <input class="mail" type="submit" value="Next Screenshot">
                </form>
                <?php
                    }
                    ?>
            </td>
        </tr>
        <tr>
            <td colspan="3"><hr /></td>
        </tr>
        <tr>
            <td width="70%"valign="top"><h4>Screenshot By: <?php echo $pilot->firstname.' '.$pilot->lastname.' - '.PilotData::GetPilotCode($pilot->code, $pilot->pilotid); ?></h4></td>
            
            <td width="15%" valign="bottom" align="center">
                <b>Rating: </b><?php echo $screenshot->rating; ?>
            </td>
            <td  width="15%" valign="bottom">
                <?php
                    if(Auth::loggedin())
                    {
                    $boost = ScreenshotsData::check_boost(Auth::$userinfo->pilotid, $screenshot->id);
                    if($boost->total > 0)
                    {echo 'Already Rated';}
                    else
                    {
                    ?>
                    <form method="post" action="<?php echo SITE_URL ?>/index.php/Screenshots/addkarma">
                    <input type="hidden" name="id" value="<?php echo $screenshot->id; ?>" />
                    <input class="mail" type="submit" value="Boost Rating"></form>
                    <?php
                    }
                    }
                    else
                    {echo 'Login To Rate Screenshot'; }
                    ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Date Submitted:</b> <?php echo date('m/d/Y', strtotime($screenshot->date_uploaded)); ?><br />
                <b>Description:</b> <?php 
                                        if(!$screenshot->file_description)
                                        {echo 'Not Available';}
                                        else
                                        {echo $screenshot->file_description;} ?>
                <br /></td>
            <td align="center"><b>Views:</b> <?php echo $screenshot->views; ?></td>
            <td>
                <!-- <form><input class="mail" type="button" value="Back To Gallery" onClick="history.go(-1);return true;"> </form> -->
                  <?php if(PilotGroups::group_has_perm(Auth::$usergroups, ACCESS_ADMIN))
                        { ?><a href="<?php echo SITE_URL ?>/index.php/Screenshots/delete_screenshot?id=<?php echo $screenshot->id; ?>"><b>Delete Screenshot</b></a><?php } else {} ?>
                <form method="link" action="<?php echo SITE_URL ?>/index.php/Screenshots">
                <input class="mail" type="submit" value="Back To Gallery"></form>
            </td>
        </tr>
        <tr>
            <td colspan="3"><hr /></td>
        </tr>
        <tr>
            <td align="center" colspan="3">
                <img src="<?php echo SITE_URL; ?>/pics/<?php echo $screenshot->file_name; ?>" style="max-width: 940px" alt="<?php echo $screenshot->file_description; ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="3"><hr /></td>
        </tr>
        <tr>
            <td colspan="2"><h4>Comments:</h4></td>
            <td>Posted By:</td>
        </tr>
        <?php if(!$comments)
            {echo '<tr><td colspan="3">No Comments</td></tr>';}
            else
            {
                echo '<tr><td colspan="3"><hr class="comment" /></td></tr>';
                foreach($comments as $comment){
                    $pilot = PilotData::getPilotData($comment->pilot_id);
                    echo '<tr>';
                    echo '<td colspan="2">'.$comment->comment.'</td>';
                    echo '<td>'.$pilot->firstname.' '.$pilot->lastname.' - '.PilotData::getPilotCode($pilot->code, $pilot->pilotid).'</td>';
                    echo '</tr>';
                    echo '<tr><td colspan="3"><hr class="comment" /></td></tr>';
                }
            }
        ?>
        <tr>
            <td colspan="3"><hr /></td>
        </tr>
        <?php if(Auth::LoggedIn())
        { ?>
        <tr>
            <td colspan="3"><h4>Add A Comment:</h4></td>
        </tr>
        <tr>
            <td colspan="3">
                <br />
                <form action="<?php echo url('/Screenshots');?>" method="post" enctype="multipart/form-data">
                <textarea name="comment" cols="50" rows="4"></textarea>
                    <br /><br />
                    <input type="hidden" name="id" value="<?php echo $screenshot->id; ?>" />
                    <input type="hidden" name="action" value="add_comment" />
                        <input class="mail" type="submit" value="Add Comment">
                </form>
            </td>
        </tr>
        <?php }
        else
        { ?>
        <tr>
            <td colspan="3">Login to add a comment</td>
        </tr>
        <?php } ?>
    </table>