<?php
require_once __DIR__ . '../../global/ViewHandler.php';
$view = ViewHandler::getCopy();
$topic = $view->getVar('topic');
$errors = $view->getVar('errors');
$actualUser = $view->getVar('actualUser');
$newComment = $view->getVar('comment');
?>
<h1><?php echo $topic->getTitle(); ?></h1>
<i>author: <?php echo $topic->getAuthor()->getUsername(); ?></i>
<p>
    <?php echo $topic->getContent(); ?>
</p>
<h2>Comments</h2>
<?php foreach ($topic->getComments() as $comment) { ?>
    <p>User: <?php $comment->getAuthor()->getUsername(); ?></p>
    <p><?php $comment->getContent(); ?></p>
<?php }
if (isset($actualUser)) { ?>
    <form method="post" action="index.php?controller=comment&action=commentCreate">
        <label>Comment</label>
        <input type="hidden" name="id" value="<?php echo $topic->getId(); ?>">
        <textarea name="content"><?php $newComment->getContent() ?></textarea>
        <input type="submit" value="OK">
    </form>
<?php } ?>

