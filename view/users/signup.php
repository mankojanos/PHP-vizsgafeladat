<?php
require_once __DIR__ . '/../../global/ViewHandler.php';
$view = ViewHandler::getCopy();
$view->setVar('title', 'Login');
$errors = $view->getErrors('errors');
?>
<h1>Sign up to page</h1>
<?php print_r($errors); ?>
<form method="post" action="index.php?controller=users&action=signUp">
    <label for="username">Username:</label>
    <input type="text" name="username">
    <label for="passwd">Password:</label>
    <input type="password" name="passwd">
    <input type="submit" name="submit" value="Login">
</form>

<p>
    <a href="index.php?controller=users&action=signUp">Sign up</a>
</p>

<?php
$view->defaultViewLoad();
?>


