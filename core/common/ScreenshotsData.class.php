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

class ScreenshotsData extends CodonData{

    public static function getscreenshots()    {
        $query = "SELECT * 
                    FROM ".TABLE_PREFIX."screenshots
                    WHERE file_approved='1'
                    ORDER BY date_uploaded DESC";

        return DB::get_results($query);
    }

    public static function getpagnated($limit)   {
        $query = "SELECT * FROM ".TABLE_PREFIX."screenshots
                    WHERE file_approved='1'
                    ORDER BY date_uploaded DESC
                    $limit";

        return DB::get_results($query);
    }

    public static function getscreenshot($id)    {
        $query = "SELECT *
                    FROM ".TABLE_PREFIX."screenshots
                    WHERE id='$id'";

        return DB::get_row($query);
    }

    public static function increase_views($id) {
        $views = self::getscreenshot($id);
        $total = $views->views + 1;
        $query = "UPDATE ".TABLE_PREFIX."screenshots SET views='$total' WHERE id='$id'";

        DB::query($query);
    }

    public static function check_boost($pid, $ssid)    {
        $query = "SELECT COUNT(`id`) AS `total`
		FROM ".TABLE_PREFIX."screenshots_ratings
                WHERE ss_id=$ssid
                AND pilot_id=$pid";
        return DB::get_row($query);
    }

    public static function increase_karma($id) {
        $rating = self::getscreenshot($id);
        $total = $rating->rating + 1;
        $query = "UPDATE ".TABLE_PREFIX."screenshots SET rating='$total' WHERE id='$id'";
        DB::query($query);
        $pid = Auth::$userinfo->pilotid;
        $query2 = "INSERT INTO ".TABLE_PREFIX."screenshots_ratings (ss_id, pilot_id)
                    VALUES ('$id', '$pid')";
        DB::query($query2);
    }

    public static function getscreenshots_toapprove()    {
        $query = "SELECT *
                    FROM ".TABLE_PREFIX."screenshots
                    WHERE file_approved='0'";

        return DB::get_results($query);
    }

    public static function approve_screenshot($id) {
        $upd = "UPDATE ".TABLE_PREFIX."screenshots SET file_approved='1' WHERE id='$id'";

        DB::query($upd);
    }

    public static function reject_screenshot($id) {
        $upd = "UPDATE ".TABLE_PREFIX."screenshots SET file_approved='2' WHERE id='$id'";

        DB::query($upd);
    }
    
    public static function delete_screenshot($id) {
        $query = "DELETE FROM ".TABLE_PREFIX."screenshots
		  WHERE id='$id'";

        DB::query($query);
    }

    public static function get_newest_screenshot() {
        $query = "SELECT id, file_name, file_description, pilot_id, UNIX_TIMESTAMP(date_uploaded) AS date FROM ".TABLE_PREFIX."screenshots WHERE file_approved='1' ORDER BY date_uploaded DESC LIMIT 1;";

        return DB::get_row($query);
    }

    public static function get_random_screenshot() {
        $query = "SELECT id, file_name, file_description, pilot_id, UNIX_TIMESTAMP(date_uploaded) AS date FROM ".TABLE_PREFIX."screenshots WHERE file_approved='1' ORDER BY RAND() LIMIT 1;";

        return DB::get_row($query);
    }

    public static function send_message($who_to, $who_from, $subject, $message)  {
        $sql="INSERT INTO ".TABLE_PREFIX."airmail (who_to, who_from, subject, message, date, time, notam)
		VALUES ('$who_to', '$who_from', '$subject', '$message', NOW(), NOW(), '0')";

        DB::query($sql);
    }

    public static function add_comment($ss_id, $pilot_id, $comment)    {
        $query ="INSERT INTO ".TABLE_PREFIX."screenshots_comments (pilot_id, ss_id, comment)
                VALUES ('$pilot_id', '$ss_id', '$comment')";

        DB::query($query);
    }

    public static function get_comments($ss_id)   {
        $query = "SELECT * FROM ".TABLE_PREFIX."screenshots_comments
                    WHERE ss_id='$ss_id'";

        return DB::get_results($query);
    }

    public static function get_previous($last) {
        $query = "SELECT id FROM ".TABLE_PREFIX."screenshots
                    WHERE id<'$last'
                    AND file_approved='1'
                    ORDER BY id DESC";

        return DB::get_row($query);
    }

    public static function get_next($last) {
        $query = "SELECT id FROM ".TABLE_PREFIX."screenshots
                    WHERE id>'$last'
                    AND file_approved='1'
                    ORDER BY id ASC";

        return DB::get_row($query);
    }

    public static function get_pilots_newscreenshot($pilot_id) {
        $query = "SELECT id, file_name, file_description, pilot_id, UNIX_TIMESTAMP(date_uploaded) AS date FROM ".TABLE_PREFIX."screenshots WHERE pilot_id='$pilot_id' AND file_approved='1' ORDER BY date_uploaded DESC LIMIT 1;";

        return DB::get_row($query);
    }
}