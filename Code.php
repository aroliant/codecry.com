<?php

if($GLOBALS['DEPLOY']){
$host       =   "localhost";
$username   =   "aroligpv_naive";
$password   =   "Oxg1hsFZpZJO";
$database   =   "aroligpv_pro_center87";
}else{
$host       =   "localhost";
$username   =   "root";
$password   =   "";
$database   =   "aro_code";
}



$GLOBALS['EXTENSION']  = array(
    'java'  => '.java',
    'cpp'   => '.cpp',
    'c'     => '.c',
    'python'=> '.py',
    'ruby'  => '.rb',
    'php'   => '.php',
    'javascript'=>'.js');




$GLOBALS['mysqli'] = new mysqli($host ,$username,$password,$database);

if ($GLOBALS['mysqli']->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function make_link($raw){
    return $GLOBALS['BASE_URL'].$raw;
}

function get_program_from_language($lang){
$query=$GLOBALS['mysqli']->query("SELECT * FROM code  WHERE lang='$lang' ORDER BY dtime DESC LIMIT 10");

$DStore = array();
$i=0;
while ( $data = $query->fetch_object()) {

    $DStore[$i]["title"]    = $data->title;
    $DStore[$i]["author"]   = $data->author;
    $DStore[$i]["time"]     = strtotime($data->dtime);
    $DStore[$i]["lang"]     = $data->lang;
    $DStore[$i]["link"]     = make_link($data->lang.'/'.$data->url_id);
$i++;
}

return $DStore;
}

function recent_programs(){
$query=$GLOBALS['mysqli']->query("SELECT * FROM code  ORDER BY dtime DESC LIMIT 5");

$DStore = array();
$i=0;
while ( $data = $query->fetch_object()) {

    $DStore[$i]["title"]    = $data->title;
    $DStore[$i]["author"]   = $data->author;
    $DStore[$i]["time"]     = strtotime($data->dtime);
    $DStore[$i]["lang"]     = $data->lang;
    $DStore[$i]["link"]     = make_link($data->lang.'/'.$data->url_id);
$i++;
}

return $DStore;
}

function get_program_from_id($id){

    $query = $GLOBALS['mysqli']->query("SELECT content,lang FROM code WHERE id=$id");
    $data = $query->fetch_object();
    return $data;

}

function get_program($lang,$url){

    $query = $GLOBALS['mysqli']->query("SELECT * FROM code WHERE lang='$lang' AND url_id='$url'");
    $data = $query->fetch_object();
    return $data;
}

function code_num_stats(){
    $query = $GLOBALS['mysqli']->query("SELECT lang,COUNT(lang) AS count  FROM code GROUP BY lang");

$DStore = array();

while ( $data = $query->fetch_object()) {

    $DStore[$data->lang] = $data->count;
}
return $DStore;

}

function livesearch($keyword,$BASE_URL){


$query=$GLOBALS['mysqli']->query("SELECT * FROM code  WHERE title LIKE '%$keyword%' LIMIT 10;");

$dataOUT = "<ul>";
while ( $data = $query->fetch_object()) {
    $dataOUT .= "<li><a href='".$BASE_URL.$data->lang."/".$data->url_id."' >".$data->title."</a></li>";
}

return $dataOUT."</ul>";

}


/* Coder Function (Code Feeding Functions) */

function get_user_drafts($username){


$query=$GLOBALS['mysqli']->query("SELECT * FROM drafts  WHERE author='$username' ORDER BY dtime DESC");

$DStore = array();
$i=0;
while ( $data = $query->fetch_object()) {
    $DStore[$i]["id"]    = $data->id;
    $DStore[$i]["title"]    = $data->title;
    $DStore[$i]["author"]   = $data->author;
    $DStore[$i]["time"]     = strtotime($data->dtime);
    $DStore[$i]["lang"]     = $data->lang;
    $DStore[$i]["link"]     = make_link($data->lang.'/'.$data->url_id);
$i++;
}

return $DStore;
}

function create_program(){

}

function load_program($id){
    $query = $GLOBALS['mysqli']->query("SELECT * FROM drafts WHERE id=$id");
    $data = $query->fetch_object();
    return $data;
}

function save_program(){

}

function publish_program(){

}

function move_to_trash_program(){

}

function check_url_link(){

}

function change_publish_state(){

}

function get_user_programs(){

}
