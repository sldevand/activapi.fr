<div class="row">
    <div class="col s12">
        <h5><?= $title ?></h5>
    </div>
</div>

<?php foreach ($cards as $card) : ?>
    <div class="row">
        <div class="col s12">
            <?php echo $card->getHtml(); ?>
        </div>
    </div>
<?php endforeach; ?>

<script src="<?= DIST . '/materializeTricks.js' ?>"></script>
