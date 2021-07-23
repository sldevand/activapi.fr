<?php
/** @var \OCFram\User $user */
/** @var string $content */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= isset($title) ? $title : 'ActivAPI'; ?></title>

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= DIST . '/index.css' ?>" type="text/css">
    <?php include("Block/favicon.phtml"); ?>
    <link rel="manifest" href="<?= ROOT . '/manifest/manifest.json' ?>">
    <script src="<?= DIST . '/index.js' ?>"></script>
</head>

<body>
    <nav class="primaryColor noselect">
        <div class="nav-wrapper textOnPrimaryColor">
            <div class="valign-wrapper">
                <div class="brand-logo center">ActivAPI</div>
                <a id="menubutton" data-activates="slide-out" class="button-collapse show-on-large">
                    <i class="material-icons">menu</i>
                </a>
                <i id="ioconnection"
                   class="valign material-icons z-depth-1 circle red lighten-3 red-text right">
                    fiber_manual_record
                </i>
                <?php if($user->isAuthenticated()) : ?>
                    <i id="authentication"
                       class="valign material-icons right">
                       verified_user
                    </i>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <ul id="slide-out" class="side-nav">
        <?php include("sideNav/sideNavMainPart.phtml"); ?>
    </ul>

    <div id="maincontent" class="container-light">
        <?php if ($user->hasFlash()) : ?>
            <script> Materialize.toast("<?= $user->getFlash() ?>", 3000); </script>
        <?php endif; ?>
        <?= $content ?>
    </div>

    <script src="<?= DIST . '/socketio.js' ?>"></script>
    <script src="<?= DIST . '/materializeTricks.js' ?>"></script>
    <script>
        $("#menubutton").sideNav({
            closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
            draggable: true, // Choose whether you can drag to open on touch screens
            menuWidth: 250
        });
    </script>
</body>
</html>
