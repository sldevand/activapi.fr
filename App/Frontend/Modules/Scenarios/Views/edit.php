<?php foreach ($cards as $card): ?>
    <div class="row">
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            <?php echo $card->getHtml(); ?>
        </div>
    </div>
<?php endforeach; ?>

<script>
    $(document).ready(function () {
        $('select').material_select();
    });
</script>
