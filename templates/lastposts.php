

    <div class="latest-posts-block">
        <div class="latest-posts-block-text">
       <p><h4> Latest blog posts </h4></p>
<?php
if(!isset($articles)) { $articles = array(); }
foreach ($articles as $filename => $article) {
    ?>


    <h5>
 <a href="<?= $article['meta']['slug']?>"><?=$article['meta']['title']?></a>
</h5>

<?php
}
?>
            </div>
</div>
