<?php
$GLOBALS['DEPLOY']  = false;
$GLOBALS['BASE_URL'] = ''.($GLOBALS['DEPLOY']  ? "http://codecry.com/" : "http://localhost/code/");

require_once 'functions.php';
require 'Code.php';

require 'vendor/autoload.php';


Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('ui/');
$GLOBALS['twig'] = new Twig_Environment($loader);
$GLOBALS['twig']->addGlobal('base_url', $GLOBALS['BASE_URL']);


//$GLOBALS['twig'] = new Twig_Environment($loader, array(
//     'cache' => 'ui/cache/',
//));


\Slim\Slim::registerAutoloader();

$app =  new \Slim\Slim();


$app->get( '/', function() {
    $CodeStats = code_num_stats();

    $totalPrograms = totalPrograms();

    $pages = ceil($totalPrograms/10)-1;

    if(!isset($_GET['page'])){
        $activePage = 0;
    }else{
        $activePage = $_GET['page'];
    }

    $RecentPrograms = recent_programs($activePage*10);

    echo $GLOBALS['twig']->render('index.html', array(
        'title'=>'Code Snippets Repository - CodeCry',
        'stats'=> $CodeStats,
        'programs'=> $RecentPrograms,
        'pages' => $pages,
        'active_page' => $activePage));
});



#Live Search
$app->get( '/livesearch',function(){

        $query = $_GET['query'];

        print(livesearch($query,$GLOBALS['BASE_URL']));

});



#Language Filter
$app->get('/language/:language/',function($language){

    if(!isset($_GET['page'])){
        $activePage = 0;
    }else{
        $activePage = $_GET['page'];
    }

    $pages = ceil(totalProgramsInLanguage($language)/10)-1;


    $CodeStats = code_num_stats();

    $Programs = get_program_from_language($language,$activePage*10);

    echo $GLOBALS['twig']->render('filter.html', array(
        'title'=>'Programs in '.ucfirst($language).' - CodeCry.com',
        'language'=>ucfirst($language),
        'stats'=> $CodeStats,
        'programs'=> $Programs,
        'pages' => $pages,
        'active_page' => $activePage
        ));


});


#Programs Page
$app->get('/:language/:url_id',function($language,$url_id){

    $Program = get_program($language,$url_id);

    $Program =   (array) $Program;

    echo $GLOBALS['twig']->render('program.html', array(
        'title'=>$Program['title'].' in '.ucfirst($Program['lang']),
        'program'=>$Program));


});


#Program Download
$app->get('/:language/:url_id/download',function($language,$url_id){

if(!empty($_SERVER['HTTP_USER_AGENT']) and preg_match('~(bot|crawl)~i', $_SERVER['HTTP_USER_AGENT'])){
header("HTTP/1.0 404 Not Found");
exit();
}

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


$app->get('/search',function(){

if(!empty($_SERVER['HTTP_USER_AGENT']) and preg_match('~(bot|crawl)~i', $_SERVER['HTTP_USER_AGENT'])){
header("HTTP/1.0 404 Not Found");
exit();
}



 echo   $_GET['query'];

});

$app->get('/legal/terms/',function(){
    echo $GLOBALS['twig']->render('terms.html');
});
$app->get('/legal/privacy/',function(){
    echo $GLOBALS['twig']->render('privacy.html');
});
$app->run();
