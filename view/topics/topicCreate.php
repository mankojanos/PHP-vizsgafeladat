<?php
require_once __DIR__ . '/../../global/ViewHandler.php';
$view = ViewHandler::getCopy();
$topic = $view->getVar('topic');
$errors = $view->getVar('errors');
$view->setVar('title', 'topicCreate');

var_dump($errors);
?>
<h1>Topic create</h1>
<form method="post" action="index.php?controller=topic&action=topicCreate">
    <label>Title</label>
    <input type="text" name="title" value="<?php echo $topic->getTitle(); ?>">
    <label>Content</label>
    <textarea name="content">
        <?php echo $topic->getContent(); ?>
    </textarea>
    <input type="submit" name="submit" value="OK">
</form>
