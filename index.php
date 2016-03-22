<?php
$GLOBALS['DEPLOY']  = false;
$GLOBALS['BASE_URL'] = ''.($GLOBALS['DEPLOY']  ? "http://codecry.com" : "http://localhost/code/");

require_once 'functions.php';
require 'Code.php';

require 'vendor/autoload.php';


Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('ui/');
$GLOBALS['twig'] = new Twig_Environment($loader);
$GLOBALS['twig']->addGlobal('base_url', $GLOBALS['BASE_URL']);


$GLOBALS['twig'] = new Twig_Environment($loader, array(
    'cache' => 'ui/cache/',
));



\Slim\Slim::registerAutoloader();




$app =  new \Slim\Slim();



$app->get( '/', function() {
    $CodeStats = code_num_stats();
    $RecentPrograms = recent_programs();


    echo $GLOBALS['twig']->render('index.html', array(
        'title'=>'GET Everything to Code | Code Snippets Library',
        'stats'=> $CodeStats,
        'programs'=> $RecentPrograms));
});





#Live Search
$app->get( '/livesearch',function(){

        $query = $_GET['query'];

        print(livesearch($query,$GLOBALS['BASE_URL']));

});


$app->get('/compiler/',function(){

    readfile('compiler/index.html');


});

$app->get('/compile',function(){

    $id = $_GET['id'];

    $Program = get_program_from_id($id);


    $Content = $Program->content;
    $Lang = $Program->lang;

if($Lang=="python"){
    $compiler_link = "http://mercury.deploy.aroliant.com/compile/python2?code=".base64_encode($Content);

}else{
    $compiler_link = "http://mercury.deploy.aroliant.com/compile/".$Lang."?code=".base64_encode($Content);

}

$content = @file_get_contents($compiler_link);

if($content === FALSE){
    echo "Error occured !";
}else{
    echo $content;
}



});


#Language Filter
$app->get('/language/:language/',function($language){

    $CodeStats = code_num_stats();

    $Programs = get_program_from_language($language);

    echo $GLOBALS['twig']->render('filter.html', array(
        'title'=>'Programs in '.$language.' - Aroliant CODE',
        'language'=>$language,
        'stats'=> $CodeStats,
        'programs'=> $Programs
        ));


});


#Programs Page
$app->get('/:language/:url_id',function($language,$url_id){

    $Program = get_program($language,$url_id);

    $Program =   (array) $Program;

    echo $GLOBALS['twig']->render('program.html', array(
        'title'=>$Program['title'].' - '.$Program['lang'],
        'program'=>$Program));


});


#Program Download
$app->get('/:language/:url_id/download',function($language,$url_id){

    $Program = get_program($language,$url_id);


    $basename = basename($Program->title.$GLOBALS['EXTENSION'][$Program->lang]);
    $filedata = $Program->content;

    // THESE HEADERS ARE USED ON ALL BROWSERS
    header("Content-Type: application-x/force-download");
    header("Content-Disposition: attachment; filename=$basename");
    header("Content-length: " . (string)(strlen($filedata)));
    header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

    // THIS HEADER MUST BE OMITTED FOR IE 6+
    if (FALSE === strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE '))
    {
        header("Cache-Control: no-cache, must-revalidate");
    }

    // THIS IS THE LAST HEADER
    header("Pragma: no-cache");

    // FLUSH THE HEADERS TO THE BROWSER
    flush();

    // CAPTURE THE FILE IN THE OUTPUT BUFFERS - WILL BE FLUSHED AT SCRIPT END
    ob_start();
    echo $filedata;


});


$app->run();
