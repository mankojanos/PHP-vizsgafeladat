<?php
require_once __DIR__ . '/../../global/ViewHandler.php';
$view = ViewHandler::getCopy();

$topics = $view->getVar('topics');
$actualUser = $view->getVar('actual_user');
$view->setVar('title', 'topics');
?>
<h1>Topics</h1>
<table border="1">
    <tr>
        <td>Title</td>
        <td>Author</td>
        <td>Actions</td>
    </tr>
    <?php foreach ($topics as $topic) {?>
        <tr>
            <td>
                <a href="index.php?controller=topic&action=readOneTopic&id=<?php echo $topic->getId(); ?>"><?php echo $topic->getTitle(); ?></a>
            </td>
            <td>
                <?php echo $topic->getAuthor()->getUsername(); ?>
            </td>
            <td>
                <?php if(isset($actualUser) && $actualUser === $topic->getAuthor()->getUsername()) {?>
                    <form method="post" action="index.php?controller=topic&action=delete">
                        <input type="hidden" name="id" value="<?php echo $topic->getId(); ?>">
                        <input type="submit" value="Delete">
                    </form>
                    <a href="index.php?controller=topic&action=editTopic&id=<?php echo $topic->getId(); ?>">Update</a>
                <?php } ?>
            </td>

        </tr>

    <?php } ?>
</table>
<?php
if(isset($actualUser)) {
    echo '<a href="index.php?controller=topic&action=topicCreate">Create topic</a>>';
}
?>

