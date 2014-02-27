<?php include 'bootstrap2/header.html' ?>

<?php
echo '<h2>' . $meta['title'] . '</h2><hr/>';

echo '<h5>Posted on ' .$meta['date'].' by ' .$meta['author'].'</h5><hr/>';
echo $content;

?>
<hr/>

    <a href="index.php">back</a>
<?php include 'bootstrap2/footer.html' ?>

