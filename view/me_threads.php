<?php defined('ACC') || exit('ACC Denied');
if (count($threads) == 0) {
	echo '<h2 class="title color-font-default">你还没有发起过任何讨论。</h2>';
} else {
	echo '<h2 class="title color-font-default">发起的讨论</h2><div class="intro color-font-default"><i class="fa-solid fa-message"></i>&nbsp;你发起的讨论都在这里，按照最新回复时间进行排序。</div><hr class="discussionHr">';
}
msgModel::clearNotification($_SESSION['uid']);
?><ul class="list-group list-group-flush">
	<?php
	foreach ($threads as $k => $t) {
	?>
		<?php $ID = $t['type'] > 0 ? userModel::getUsername($t['uid']) : substr(md5($t['uid'] . $t['title'] . $t['tid']), -8); //用户id，存在昵称的时候返回昵称，否则返回生成的id
		?>
		<li class="discussion list-group-item list-group-item-action color-font-grey" tid="<?php echo $t['tid']; ?>">
			<div class="stretched-link d-flex justify-content-between" onclick="window.location.href='view.php?id=<?php echo $t['tid']; ?>'">
				<div class="contentsContainer">
					<?php if (subscriptionModel::isSubscribed($t['tid'])) { ?>

						<div class="inline subscribeBtn badge color-badge-active">
							<i class="fa-solid fa-bookmark"></i>
						</div>

					<?php } ?>
					<span class="text-nickname color-font-important"><?php echo $t['name'] ? $t['name'] : "匿名"; ?></span>
					<span class="text-title color-font-default">
						<?php
						$indexTitle = base64_decode($t['title'], true) ? base64_decode($t['title'], true) : $t['title'];
						echo $indexTitle; ?></span>
					<span class="text-date color-font-secondary date" date="<?php echo date('YmdHis', $t['pubtime']); ?>"></span>
					<p class="text-content color-font-default ">
						<?php
						$indexContent = base64_decode($t['content'], true) ?: $t['content'];
						$indexContent = str_replace('<div class="fandaoDoodle">', "", $indexContent);
						$indexContent = str_replace('</div>', "", $indexContent); //清理doodle										
						$indexContent = preg_replace('/upload\//', 'upload/thumbnail/', $indexContent);
						echo $indexContent;
						?></p>
					<?php echo $t['SAGE'] == 0 ? '' : '<div class="sage color-font-danger"><i class="fa-solid fa-ban"></i>&nbsp;本讨论已经被下沉</div>' ?>
				</div>
				<div class="buttonsContainer">
					<div class="subBtn">
						<div class="badge <?php echo threadlikesModel::isLiked($t['tid']) ? 'color-badge-active' : 'color-badge-default' ?>"><i class=" fa-solid fa-thumbs-up"></i>&nbsp;<?php echo $reply_num = $msg->countLikes($t['tid']); ?></div>
						<div class="badge color-badge-default"><i class="fa-solid fa-comment-dots"></i>&nbsp;<?php echo $reply_num = $msg->countReplies($t['tid']); ?></div>

					</div>

				</div>

			</div>
		</li>

	<?php } ?>
</ul>

<?php require('view/pagination.php'); ?>