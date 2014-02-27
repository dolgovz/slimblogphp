<?php
foreach($archives as $a) {

    ?>

<h3><?=$a['meta']['title']?></h3>
    <p><?=$a['content']?></p>
<?php
}