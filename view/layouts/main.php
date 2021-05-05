<?php
$view = ViewHandler::getCopy();
$actualUser = $view->getVar('actualuser');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php $view->getVar('title', 'MVC forum') ?></title>
</head>
<body>
<h1>Forum Page</h1>
<ul>
    <li><a href="index.php?controller=topics&action=index">Topics</a></li>
    <?php
    if(isset($actualUser)) {
        echo '<li> Welcome ' . $actualUser .'. <a href="index.php?controller=user&action=logOut">logout</a></li>';
    } else {
        echo '<li><a href="index.php?controller=user&action=login">Login</a></li>';
    }
    ?>
</ul>
<div>
    <?php
        echo $view->getMessageSession();
    ?>
    <br>
    <?php
    echo $view->getView(ViewHandler::DEFAULT_VIEW);
    ?>
</div>
<footer>Developer: MJ</footer>
</body>
</html>


