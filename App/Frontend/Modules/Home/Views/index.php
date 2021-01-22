<?php foreach ($cards as $card) : ?>
    <div class="row">
        <div class="col s12">
            <?php echo $card->getHtml(); ?>
        </div>
    </div>
<?php endforeach; ?>
