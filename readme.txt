ScreenshotCenter 3.0

phpVMS module to create a screenshot uploading and display system for your phpVMS based VA.

Developed by:
simpilot
www.simpilotgroup.com

Developed on:
phpVMS 2.1.940
php 5.2.11
mysql 5.0.51
apache 2.2.11

Install:

-Download the attached package.
-unzip the package and place the files as structured in the distribution in your root phpVMS install.
-use the screenshotcenter.sql file to create the tables needed in your sql database using phpmyadmin or similar.
-make sure that the file has created three tables in your database - screenshots, screenshots_comments, and secreenshots_ratings.

Create a link for your pilots to get to the ScreenshotCenter

<?php echo url('/Screenshots'); ?>

-Be sure that the "pics" folder ends up in the root as it is structured in the download and has write permissions.

-Templates are generic and will need to be skinned to your site, they do how ever depend on the native phpVMS "error" and "success" divisions, you need to have them in your css or replace them with your own.

Functions Available.

To display the newest approved screenshot on your site:

<?php Screenshots::show_newest_screenshot(); ?>

To display a random approved screenshot on your site:

<?php Screenshots::show_random_screenshot(); ?>

To display the newest approved screenshot of a pilot::

<?php Screenshots::get_pilots_newscreenshot('pilotid#'); ?>

The links to upload, rate, and add comments are only available to logged in members.
The screenshot approval link in the gallery is only available to administrators.

Released under the following license: 
Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License 