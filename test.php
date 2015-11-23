<?php

$host      = "localhost";
$username  = "root";
$password  = "";
$database  = "aro_code";

$mysqli = new mysqli($host ,$username,$password,$database);


function make_url_id($data){
    $data = strtolower($data);
    $data = str_replace(" ","-",$data);
    $out = array();
    preg_match_all("/[a-z-]/", $data,$out);;
    return  implode("",$out[0]);

}

function update_url_id_from_title(){

    $query = $mysqli->query("SELECT * FROM code");

    while($data = $query->fetch_object()){

    echo make_url_id($data->title).'<br/>';

    $to_url = make_url_id($data->title);

    $queryInsert = $mysqli->query("UPDATE code SET url_id = '$to_url' WHERE title = '$data->title'");


    }

}



