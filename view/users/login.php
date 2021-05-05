<?php
require_once __DIR__ . '/../../global/ViewHandler.php';
$view = ViewHandler::getCopy();
$view->setVar('title', 'Login');
$errors = $view->getVar('errors');
?>
<h1>Login to page</h1>
<?php print_r($errors); ?>
<form method="post" action="index.php?controller=user&action=login">
    <label for="username">Username:</label>
    <input type="text" name="username">
    <label for="passwd">Password:</label>
    <input type="password" name="passwd">
    <input type="submit" name="submit" value="Login">
</form>

<p>
    <a href="index.php?controller=user&action=signUp">Sign up</a>
</p>

<?php
$view->defaultViewLoad();
?>


