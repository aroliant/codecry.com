<?php

if($GLOBALS['DEPLOY']){
$host       =   "localhost";
$username   =   "aroliard_naive";
$password   =   "Oxg1hsFZpZJO";
$database   =   "aroliard_pro_center87";
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

function get_program_from_language($lang,$offset){


$query=$GLOBALS['mysqli']->query("SELECT * FROM code  WHERE lang='$lang' ORDER BY dtime DESC LIMIT $offset,10");

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

function recent_programs($offset){

$query=$GLOBALS['mysqli']->query("SELECT * FROM code  ORDER BY dtime DESC LIMIT $offset,10");

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
    $data->enc_content = $data->content;
    $data->content = htmlspecialchars_decode($data->content,ENT_QUOTES);
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
    $dataOUT .= "<li><a href='http://codecry.com/".$data->lang."/".$data->url_id."' >".$data->title."</a></li>";
}

return $dataOUT."</ul>";

}


function totalPrograms(){
    return $GLOBALS['mysqli']->query("SELECT COUNT(*) AS TOTAL FROM code")->fetch_object()->TOTAL;
}

function totalProgramsInLanguage($lang){
    return $GLOBALS['mysqli']->query("SELECT COUNT(*) AS TOTAL FROM code WHERE lang='$lang'")->fetch_object()->TOTAL;
}


function alt_available($url_id){

$query=$GLOBALS['mysqli']->query("SELECT lang FROM code  WHERE url_id = '$url_id'");

$DStore = array();

while ( $data = $query->fetch_object()) {

array_push($DStore,$data->lang);

}

return $DStore;
}

function random_programs(){

$query = $GLOBALS['mysqli']->query("SELECT * FROM code ORDER BY RAND() LIMIT 10");

$DStore = array();

while ( $data = $query->fetch_object()) {

array_push($DStore,array(
    'lang' => $data->lang,
    'title' => $data->title,
    'url_id' => $data->url_id
    ));

}

return $DStore;

}