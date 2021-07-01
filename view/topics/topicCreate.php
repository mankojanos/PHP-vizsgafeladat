<?php
require_once __DIR__ . '/../../global/ViewHandler.php';
$view = ViewHandler::getCopy();
$topic = $view->getVar('topic');
$errors = $view->getVar('errors');
$view->setVar('title', 'topicCreate');

?>
<h1>Topic create</h1>
<form method="post" action="index.php?controller=topic&action=topicCreate">
    <label>Title</label>
    <br>
    <input type="text" name="title" value="<?php echo $topic->getTitle(); ?>" size="48">
    <br>
    <label>Content</label>
    <br>
    <textarea name="content" rows="6" cols="50">
        <?php echo $topic->getContent(); ?>
    </textarea>
    <br>
    <input type="submit" name="submit" value="OK">
</form>
