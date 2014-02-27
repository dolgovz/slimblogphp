

<div class="tags">
    <div class="tags-text">
       <p><h4>Tags </h4></p>
<?php
if(!isset($articles)) { $articles = array(); }
foreach ($articles as $filename => $article) {
    ?>


    <h5>
 <a href="<?= $article['meta']['slug']?>"><?=$article['meta']['tag']?></a>
</h5>

<?php
}
?>
        </div>
</div>
