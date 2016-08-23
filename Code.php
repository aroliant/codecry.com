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

$stack = array();

while ( $data = $query->fetch_object()) {

    array_push($stack,array(
        "title" => $data->title,
        "author"=> $data->author,
        "time"  => strtotime($data->dtime),
        "lang"  => $data->lang,
        "link"  => make_link($data->lang.'/'.$data->url_id),
        "lang_legal" => toFullName($data->lang)));

}

return $stack;
}

function recent_programs($offset){

$query=$GLOBALS['mysqli']->query("SELECT * FROM code  ORDER BY dtime DESC LIMIT $offset,10");

$stack = array();

while ( $data = $query->fetch_object()) {

    array_push($stack, array(
        "title" =>  $data->title,
        "author"=>  $data->author,
        "time"  =>  strtotime($data->dtime),
        "lang"  =>  $data->lang,
        "link"  =>  make_link($data->lang.'/'.$data->url_id),
        "lang_legal" => toFullName($data->lang)));

}
return $stack;

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
    $data->notes = htmlspecialchars_decode($data->notes,ENT_QUOTES);
    $data->lang_legal = toFullName($data->lang);
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

$stack = array();

while ( $data = $query->fetch_object()) {    

array_push($stack,array(
    'lang' => $data->lang,
    'title' => $data->title,
    'url_id' => $data->url_id,
    'lang_legal' => toFullName($data->lang)
    ));

}

return $stack;

}

/* Programs by Language Page Meta Description */

$description = array(
    "c" => "C Programs",
    "cpp" => "");

function toFullName($lang){

    switch($lang){

        case 'c'        : return 'C';
        case 'cpp'      : return 'C++';
        case 'python'   : return 'Python';
        case 'php'      : return 'PHP';
        case 'ruby'     : return 'Ruby';
        case 'perl'     : return 'Perl';
        case 'java'     : return 'Java';
        case 'javascript'   : return 'JavaScript';
        case 'csharp'   : return 'C#';
        case 'objc'     : return 'Objective-C';
        case 'swift'    : return 'Swift';
        case 'ocaml'    : return 'OCaml';
        case 'go'       : return 'GO';
        case 'clojure'  : return 'Clojure';
        case 'nodejs'   : return 'Node.JS';
        case 'android'  : return 'Android';

        default         : return 0;

    }

}