<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$blog = new \Slim\Blog();

$app->config(array(
    'templates.path' => './templates',
    'article.path' => './articles'
));

$app->get('/about', function () use ($app) {
    $data = array(
        'heading' => 'About page',
        'message' => 'This page is an example of static route, rendering a php file.'
    );
    $app->render('about.php', $data);

});


$app->get('/admin', function () use ($app) {

    $path = $app->config('article.path');
    $dir = new DirectoryIterator($path);
    $articles = array();
    foreach ($dir as $file) {
        if ($file->isFile()) {

            $handle = fopen($path . '/' . $file->getFilename(), 'r');
            $content = stream_get_contents($handle);
            $content = explode("\n\n", $content);
            $rawMeta = array_shift($content);
            $meta = json_decode($rawMeta, true);

            $content = implode("\n\n", $content);

            $content =  substr(strip_tags($content), 0, 200);

            $articles[$file->getFilename()] = array('meta' => $meta, 'content' => $content);

        }


    }

    $app->render('admin.php',array('articles' => $articles));

    return $articles;


});







$app->get('/:article',  function ($article) use ($app) {

    $path = $app->config('article.path');

    $handle = fopen($path . '/' . $article . '.txt', 'r');
    //fopen -- Открывает файл или URL
    $content = stream_get_contents($handle);
    //stream_get_contents -- Reads remainder of a stream into a string
    $content = explode("\n\n", $content);
    //explode -- Разбивает строку на подстроки
    //var_dump($content);exit;
    $rawMeta = array_shift($content);
    //array_shift --  Извлечь первый элемент массива
    $meta = json_decode($rawMeta, true);
    //json_decode — Декодирует JSON строку
    //Принимает закодированную в JSON строку и преобразует ее в переменную PHP.
    $content = implode("\n\n", $content);
    //implode -- Объединяет элементы массива в строку
    $article = array('meta' => $meta, 'content' => $content);
    $app->render('article.php', $article);

});

//function edit($app, $article){
//    $path = $app->config('article.path');
//    $handle = fopen($path . '/' . $article . '.txt', 'r');
//    //fopen -- Открывает файл или URL
//    $content = stream_get_contents($handle);
//    //stream_get_contents -- Reads remainder of a stream into a string
//    $content = explode("\n\n", $content);
//    //explode -- Разбивает строку на подстроки
//    //var_dump($content);exit;
//    $rawMeta = array_shift($content);
//    //array_shift --  Извлечь первый элемент массива
//    $meta = json_decode($rawMeta, true);
//    //json_decode — Декодирует JSON строку
//    //Принимает закодированную в JSON строку и преобразует ее в переменную PHP.
//    $content = implode("\n\n", $content);
//    //implode -- Объединяет элементы массива в строку
//    $article = array('meta' => $meta, 'content' => $content);
//
//}



$app->get('/', function () use ($app) {

    $path = $app->config('article.path');
    $dir = new DirectoryIterator($path);
    $articles = array();
    foreach ($dir as $file) {
        if ($file->isFile()) {

            $handle = fopen($path . '/' . $file->getFilename(), 'r');
            $content = stream_get_contents($handle);
            $content = explode("\n\n", $content);
            $rawMeta = array_shift($content);
            $meta = json_decode($rawMeta, true);

            $content = implode("\n\n", $content);

            $content =  substr(strip_tags($content), 0, 200);

            $articles[$file->getFilename()] = array('meta' => $meta, 'content' => $content);





        }


    }
//    var_dump($articles);exit;

//////////////////////////////////


    $app->render('test.php',array('articles' => $articles));


return $articles;


});

function edit($app, $article){
    $path = $app->config('article.path');
    $handle = fopen($path . '/' . $article . '.txt', 'r');
    //fopen -- Открывает файл или URL
    $content = stream_get_contents($handle);
    //stream_get_contents -- Reads remainder of a stream into a string
    $content = explode("\n\n", $content);
    //explode -- Разбивает строку на подстроки
    //var_dump($content);exit;
    $rawMeta = array_shift($content);
    //array_shift --  Извлечь первый элемент массива
    $meta = json_decode($rawMeta, true);
    //json_decode — Декодирует JSON строку
    //Принимает закодированную в JSON строку и преобразует ее в переменную PHP.
    $content = implode("\n\n", $content);
    //implode -- Объединяет элементы массива в строку
    return array('meta' => $meta, 'content' => $content);

}
// Admin Delete.
$app->get('/admin/delete/:param1', function($param1) use ($app) {


    $path = $app->config('article.path');

    $filePath = $path . DIRECTORY_SEPARATOR . $param1 . '.txt';

    if (!is_file($filePath)) {
        throw new \Exception('Wrong file requested');
    }

    unlink($filePath);

    $app->redirect('/admin');
});

// Admin Edit
$app->map('/admin/edit/:slug', function($slug) use ($app){
    $path = $app->config('article.path');
    $filePath = $path . DIRECTORY_SEPARATOR . $slug . '.txt';

    if (!is_file($filePath)) {
        throw new \Exception('Wrong file requested');
    }

    $article = edit($app , $slug);

    if ($app->request()->getMethod() == 'POST') {
        $newArticle['meta'] = array(
            'title' => $app->request()->post('title'),
            'date' => $app->request()->post('date'),
            'slug' => $app->request()->post('slug'),
            'author' => $app->request()->post('author'),
            'tag' => $app->request()->post('tag'));

        $newArticleContent['content'] = array(
            'content' => $app->request()->post('content'));





        $meta = json_encode($newArticle['meta'], JSON_PRETTY_PRINT);

        $content = implode("\n\n", $newArticleContent['content']);
        file_put_contents($filePath, $meta . "\n\n". $content);


        $app->redirect('/admin/edit/'.$slug);


    }


    $app->render('edit.php',array('article' => $article));
})->via('GET', 'POST');




 //Admin Add.
$app->map('/admin/add', function() use ($app){

    $meta = array(
        'title' => $app->request()->post('title'),
        'date' => $app->request()->post('date'),
        'slug' => $app->request()->post('slug'),
        'author' => $app->request()->post('author'),
        'tag' => $app->request()->post('tag'));

    $content = array(
        'content' => $app->request()->post('content'));

    if ($app->request()->getMethod() == 'POST') {
        $newArticle['meta'] = $meta;

        $newArticleContent['content'] = $content;


        $path = $app->config('article.path');
        $filePath = $path . DIRECTORY_SEPARATOR . $app->request()->post('slug') . '.txt';


            $meta = json_encode($newArticle['meta'], JSON_PRETTY_PRINT);

            $content = implode("\n\n", $newArticleContent['content']);
            file_put_contents($filePath, $meta . "\n\n". $content);


        $app->redirect('/admin');


    }
    $app->render('add.php', array('meta' => $meta, 'content' => $content));

})->via('GET', 'POST');









$app->get('/archives(/:yyyy(/:mm(/:dd)))', function () use ($app) {

//    echo "huj";exit;
    $args = func_get_args();

//    var_dump($args);exit;
    $path = $app->config('article.path');
    $dir = new DirectoryIterator($path);
    $articles = array();
    foreach ($dir as $file) {
        if ($file->isFile()) {

            $handle = fopen($path . '/' . $file->getFilename(), 'r');
            $content = stream_get_contents($handle);
            $content = explode("\n\n", $content);
            $rawMeta = array_shift($content);
            $meta = json_decode($rawMeta, true);
            $content = implode("\n\n", $content);
            $articles[$file->getFilename()] = array('meta' => $meta, 'content' => $content);
        }
    }





    $archives = array();
    if (count($args) > 0) {

        $dateFormat = function ($args, $format) {
            $temp_date = is_array($args) ? implode('-', $args) : $args;
            $date = DateTime::createFromFormat($format, $temp_date);
//var_dump($date);

            return $date->format("Y");
        };

        switch (count($args)) {
            case 1 : //only year is present

                $format = 'Y';
                $date = $dateFormat($args, $format);
                break;
            case 2 : //year and month are present
                $format = 'Y-m';
                $date = $dateFormat($args, $format);
                break;
            case 3 : //year, month and date are present
                $format = 'Y-m-d';
                $date = $dateFormat($args, $format);
                break;
        }
        foreach ($articles as $article) {
            if ($dateFormat($article['meta']['date'], $format) == $date) {
                $archives[] = $article;

            }
        }
//echo "huj";
//        var_dump($articles);exit;
    } else {
        $archives = $articles;
    }


exit;

    $app->render('archives.php', array('archives' => $archives));


})->conditions(
    array(
        'yyyy' => '(19|20)\d\d'
    ,'mm'=>'(0[1-9]|1[0-2])'
    ,'dd'=>'(0[1-9]|[1-2][0-9]|3[0-1])'

    )
    );

//$app->get('/:param1/:param2', function ($param1, $param2) use ($app){
//    echo $param1 . ' - ' . $param2;
//
//});


//$app->get('/test', function () use ($app) {
//        $app->render('test.php');
//});


$app->run();
