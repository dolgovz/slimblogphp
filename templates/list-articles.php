
<div class="posts-list">


<?php
if(!isset($articles)) { $articles = array(); }
foreach ($articles as $filename => $article) {

    ?>
    <h1><?=$article['meta']['title']?></h1>


    <h6>Posted on <?=$article['meta']['date']?>  by <?=$article['meta']['author']?></h6>



    <p><?=$article['content']?><br><a href="<?= $article['meta']['slug']?>">Read more >> </a></p>
    <hr/>
<?php
}
?>

</div>