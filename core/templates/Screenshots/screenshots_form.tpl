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
?>

<form action="<?php echo url('/Screenshots');?>" method="post" enctype="multipart/form-data">
    <table class="profiletop">
        <tr>
            <td width="50%" valign="top">
                <h5>Put Your Screenshot upload terms:</h5>
                <ul>
                    <li>Rule 1.</li>
                    <li>Rule 2.</li>
                </ul>
            </td>
            <td>
                <p>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>">
                </p>

                <p>
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                    <label for="file">File to upload:</label><br />
                    <input class="mail" id="file" type="file" name="uploadedfile" />
                </p>

                <p>
                    <label for="description">Enter a description for your screenshot:</label><br />
                    <textarea class="mail" name="description" rows="5" cols="50"></textarea>
                </p>

                <p>
                    <input type="hidden" name="action" value="save_upload" />
                    <input class="mail" type="submit" value="Upload File!">
                </p>
            </td>
        </tr>
    </table>
</form>

<center>
    <br />
    <form method="link" action="<?php echo SITE_URL; ?>/index.php/screenshots">
        <input class="mail" type="submit" value="Return To The Gallery"></form>
</center>       