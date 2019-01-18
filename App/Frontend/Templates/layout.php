<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'ActivAPI'; ?>
    </title>

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= CSS . '/materialize.min.css' ?>" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS . '/style-parent.css' ?>" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS . '/icon-fonts.css' ?>" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS . '/materialdesignicons.css' ?>" type="text/css">
    <link rel="stylesheet" href="<?= CSS . '/style-light.css' ?>" type="text/css"/>
    <script src="<?= JS_UTILS . '/jquery-3.2.1.min.js' ?>"></script>
    <script src="<?= JS_UTILS . '/materialize.min.js' ?>"></script>
    <script src="<?= JS_UTILS . '/moment.js' ?>"></script>
    <script src="<?= NODE_MODULES . '/socket.io-client/dist/socket.io.js' ?>"></script>
    <script src="<?= JS_UTILS . '/requirejs/require.js' ?>"></script>
</head>

<body>

<nav class="primaryColor noselect">
    <div class="nav-wrapper textOnPrimaryColor">
        <div class="brand-logo center">ActivAPI</div>
        <a id="menubutton" data-activates="slide-out" class="button-collapse show-on-large">
            <i class="material-icons">menu</i>
        </a>
    </div>
</nav>

<ul id="slide-out" class="side-nav">
    <?php include("sideNav/sideNavMainPart.html"); ?>
</ul>

<div id="maincontent" class="container-light">
    <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

    <?= $content ?>
</div>

<script>
    $("#menubutton").sideNav({

        closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
        draggable: true, // Choose whether you can drag to open on touch screens
        menuWidth: 250
    });
</script>

</body>
</html>
