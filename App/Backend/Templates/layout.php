<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'ActivAPI' ?>
    </title>

    <meta charset="utf-8"/>

    <link rel="stylesheet" href="css/Envision.css" type="text/css"/>
</head>

<body>
<div id="wrap">
    <header>
        <h1><a href="/">ActivAPI</a></h1>

    </header>

    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>

        </ul>
    </nav>

    <div id="content-wrap">
        <section id="main">
            <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

            <?= $content ?>
        </section>
    </div>

    <footer></footer>
</div>
</body>
</html>