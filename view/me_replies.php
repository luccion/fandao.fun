<?php defined('ACC') || exit('ACC Denied');
if (count($threads) == 0) {
	echo '<h2 class="title color-font-default">你还没有回复过。</h2>';
} else {
	echo '<h2 class="title color-font-default">发出的回复</h2><div class="intro color-font-default"><i class="fa-solid fa-comment-dots"></i>&nbsp;你发出的回复都在这里，按照时间进行排序。</div>';
}
foreach ($threads as $k => $t) {
?>

	<div class="discussion discussionInMe" tid="<?php echo $t['tid']; ?>">

		<div class="block list-group-item-action color-font-grey position-relative color-background-grey">
			<hr>
			<a class="none-decoration stretched-link" href="view.php?id=<?php echo $t['tid']; ?>">
				<span class="avatarSVG">
					<?php echo userModel::getCurrentAvatarSVG($t['name']);
					?></span>
				<span class="text-nickname color-font-important"><?php echo $t['name'] ? $t['name'] : "匿名"; ?></span>
				<span class="text-title color-font-default"><i class="fa-solid fa-quote-left"></i>&nbsp;<?php $indexTitle = base64_decode($t['title'], true) ? base64_decode($t['title'], true) : $t['title'];
																										echo $indexTitle;  ?> <i class="fa-solid fa-quote-right"></i></span>
				<span class="text-date color-font-secondary date" date="<?php echo date('YmdHis', $t['pubtime']); ?>"></span>

				<div class="text-content color-font-default ">
					<?php
					$indexContent = base64_decode($t['content'], true) ?: $t['content'];
					$indexContent = str_replace('<div class="fandaoDoodle">', "", $indexContent);
					$indexContent = str_replace('</div>', "", $indexContent); //清理doodle										
					$indexContent = preg_replace('/upload\//', 'upload/thumbnail/', $indexContent);
					echo $indexContent; ?></div>

				<?php echo $t['SAGE'] == 0 ? '' : '<div class="sage color-font-danger"><i class="fa-solid fa-ban"></i>&nbsp;本讨论已经被下沉（<abbr  title="该讨论不会因为新回复而被顶到页首">?</abbr>）</div>' ?>
			</a>

			<hr class="discussionHr" />
		</div>
		<ul class="list-group  list-group-flush  color-background">
			<?php

			$topFloor = $msg->getNextFloor($t['tid']) - 1;
			foreach ($t['replies'] as $key => $value) {
				$flag = false;
				if (!isset($t['replies'][$key - 1])) {
					if ($value['floor'] < $topFloor) {
						$flag = true;
					}
				} elseif ($t['replies'][$key - 1]['floor'] - 1 != $value['floor']) {
					$flag = true;
				}

			?>
				<li class="reply list-group-item" floor=<?php echo $value['floor']; ?>>
					<div class="floorNumber color-font-secondary"><i class="fa-solid fa-hashtag"></i>
						<?php echo catModel::fontawesomeNumber($value['floor']); ?>
					</div> <span class="text-date color-font-secondary date" date="<?php echo date('YmdHis', $value['reptime']); ?>"></span>
					<span class="text-nickname color-font-important"><?php echo $value['name'] ? $value['name'] : "匿名"; ?></span>
					<div class="text-content color-font-default ">
						<?php
						$replyContent = base64_decode($value['content'], true) ?: $value['content'];
						$replyContent = str_replace('<div class="fandaoDoodle">', "", $replyContent);
						$replyContent = str_replace('</div>', "", $replyContent); //清理doodle										
						$replyContent = preg_replace('/upload\//', 'upload/thumbnail/', $replyContent);
						echo $replyContent; ?>
					</div>
				</li>
			<?php
				$flag = false;
				if (!isset($t['replies'][$key + 1])) {
					if ($value['floor'] > 1) {
						$flag = true;
					}
				}
			}
			?>
	</div>

<?php } ?>
<?php require('view/pagination.php'); ?>