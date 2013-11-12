<?php
/* File   : dbconnect.php
   Subject: CS160 demo
   Authors: Chris Tseng
   Version: 1.0.2
   Date   : Nov 1, 2004
   Description: create database connection to the selected database
*/
    $conn = @mysql_connect("localhost","sjsucsor_160g4","groupfourdb")
    //$conn = @mysql_connect("localhost", "root")
    or die("Could not connect to localhost");
    //Create a database named cs160 and load guestbook.mysql into it before using this example
    mysql_select_db("sjsucsor_160g4fall2013", $conn)
    or die("Could not select database");

?>