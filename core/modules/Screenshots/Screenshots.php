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

class Screenshots extends CodonModule {

    public function index() {

        if($this->post->action !='')
        {
        if($this->post->action == 'last') {
            $this->large_screenshot();
        }
        if($this->post->action == 'save_upload') {
            $this->save_upload();
        }
        if($this->post->action == 'add_comment') {
            $this->add_comment();
        }
        if($this->post->action == 'approve_screenshot') {
            $this->approve_screenshot();
        }
        if($this->post->action == 'reject_screenshot') {
            $this->reject_screenshot();
        }
        if($this->post->action == 'delete_screenshot') {
        $this->delete_screenshot();
        }
        }
        else
        {
            if (isset($_GET['page'])){
                $page = (int) $_GET['page'];
            }
            else
            {
                $page = 1;
            }
            // how many records per page
            $size = 8;
            $tot = "SELECT COUNT(*)AS total FROM ".TABLE_PREFIX."screenshots";
            $total = DB::get_row($tot);
            $this->set('size', $size);
            $this->set('page', $page);
            $this->set('total', $total->total);
            $this->show('Screenshots/screenshots_viewer');
        }
    }

    protected function save_upload() {

        if ((($_FILES["uploadedfile"]["type"] == "image/x-png")
            || ($_FILES["uploadedfile"]["type"] == "image/jpeg")
            || ($_FILES["uploadedfile"]["type"] == "image/pjpeg")
            || ($_FILES["uploadedfile"]["type"] == "image/gif"))
            && ($_FILES["uploadedfile"]["size"] < 2000000))
        {

            if ($_FILES["file"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br />";
            }
            $target_path = "pics/";

            $_FILES['uploadedfile']['name'] = time().'_'.$_FILES['uploadedfile']['name'];

            $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

            if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
                echo '<div id="success" align="center">The file '.  basename( $_FILES['uploadedfile']['name']).' has been uploaded - An Administrator will approve the screenshot prior to it being available in the gallery.</div>';

                $file_name = $_FILES['uploadedfile']['name'];
                $pilot_id = Auth::$userinfo->pilotid;
                $description = DB::escape($this->post->description);
                if (!$description)
                    {$description = 'No Description Provided';}
                $query = "INSERT INTO ".TABLE_PREFIX."screenshots (file_name, file_description, pilot_id, date_uploaded)
                VALUES ('$file_name', '$description', '$pilot_id', NOW())";

                DB::query($query);

            } else {
                echo '<div id="error" align="center">There was an error uploading the file, please try again!';
            }

        }
        else {echo'<div id="error" align="center">That sucks! Your upload is not a jpg or gif file, or may be too big!</div>';}

        if (isset($_GET['page'])){
                $page = (int) $_GET['page'];
            }
            else
            {
                $page = 1;
            }
            // how many records per page
            $size = 8;
            $tot = "SELECT COUNT(*)AS total FROM ".TABLE_PREFIX."screenshots";
            $total = DB::get_row($tot);
            $this->set('size', $size);
            $this->set('page', $page);
            $this->set('total', $total->total);
            $this->show('Screenshots/screenshots_viewer');
    }

    public function upload()    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error');
            return;
        }
        $this->show('Screenshots/screenshots_form');
    }

    public function approval_list()    {
        $this->set('screenshots', ScreenshotsData::getscreenshots_toapprove());
        $this->show('Screenshots/screenshots_approval');
    }

    protected function approve_screenshot() {
        $id = DB::escape($this->post->id);
        $who_to = DB::escape($this->post->pid);
        $file = DB::escape($this->post->file);
        $who_from = Auth::$userinfo->pilotid;
        $subject = 'Screenshot Approval';
        $message = "Your screenshot has been approved and is now in the screenshot gallery. ($file)";
        ScreenshotsData::approve_screenshot($id);
        ScreenshotsData::send_message($who_to, $who_from, $subject, $message);
        header('Location: '.url('/Screenshots/approval_list'));
    }

    protected function reject_screenshot() {
        $id = DB::escape($this->post->id);
        $who_to = DB::escape($this->post->pid);
        $file = DB::escape($this->post->file);
        $who_from = Auth::$userinfo->pilotid;
        $subject = 'Screenshot Rejection';
        $message = "Unfortunately your screenshot has been rejected and removed from the database. Contact a member of management if you have any questions. ($file)";
        ScreenshotsData::reject_screenshot($id);
        ScreenshotsData::send_message($who_to, $who_from, $subject, $message);
        header('Location: '.url('/Screenshots/approval_list'));
    }
    
     public function delete_screenshot() {
        $id = $_GET['id'];
        ScreenshotsData::delete_screenshot($id);
        header('Location: '.url('/Screenshots/'));
                $this->set('message', 'Screenshot Deleted!');
        $this->render('core_success');

     }

    public function show_newest_screenshot()    {
        $screenshot = ScreenshotsData::get_newest_screenshot();
        $this->set('screenshot', $screenshot);
        $this->set('date', date('m/d/Y', $screenshot->date));
        $this->show('Screenshots/screenshots_new');
    }

    public function show_random_screenshot()    {
        $screenshot = ScreenshotsData::get_random_screenshot();
        $this->set('screenshot', $screenshot);
        $this->set('date', date('m/d/Y', $screenshot->date));
        $this->show('Screenshots/screenshots_random');
    }

    public function large_screenshot()  {
        $id = $_GET['id'];
        if(!$id)
            {$id = DB::escape($this->post->id);}
        $pid = Auth::$userinfo->pilotid;
        
        ScreenshotsData::increase_views($id);
        $this->set('comments', ScreenshotsData::get_comments($id));
        $this->set('screenshot', ScreenshotsData::getscreenshot($id));
        $this->show('Screenshots/screenshots_large');
    }

    public function addkarma()  {
        $id = DB::escape($this->post->id);
        ScreenshotsData::increase_karma($id);
        header('Location: '.url('/Screenshots/large_screenshot?id='.$id.''));
    }

    protected function add_comment()  {
        $ss_id = DB::escape($this->post->id);
        $comment = DB::escape($this->post->comment);
        $pilot_id = Auth::$userinfo->pilotid;

        ScreenshotsData::add_comment($ss_id, $pilot_id, $comment);
        header('Location: '.url('/Screenshots/large_screenshot?id='.$ss_id.''));
    }

    public function previous()   {
        $last = DB::escape($this->post->id);

        $id = ScreenshotsData::get_previous($last);

        ScreenshotsData::increase_views($id->id);
        $this->set('comments', ScreenshotsData::get_comments($id->id));
        $this->set('screenshot', ScreenshotsData::getscreenshot($id->id));
        $this->show('Screenshots/screenshots_large');
    }

    public function get_pilots_newscreenshot($pilot_id)    {
        $screenshot = ScreenshotsData::get_pilots_newscreenshot($pilot_id);
        $this->set('screenshot', $screenshot);
        $this->set('date', date('m/d/Y', $screenshot->date));
        $this->show('Screenshots/screenshots_pilot');
    }
}