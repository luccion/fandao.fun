<?php defined('ACC') || exit('ACC Denied');
if (count($threads) == 0) {
	echo '<h2 class="title color-font-default">订阅为空。</h2>';
} else {
	echo '<h2 class="title color-font-default">我的订阅</h2><div class="intro color-font-default"><i class="fa-solid fa-bookmark"></i>显示你订阅的讨论，按照最新回复时间进行排序。</div><hr class="discussionHr" />';
}
?>
<ul class="list-group  list-group-flush">
	<?php
	foreach ($threads as $k => $t) {
	?>
		<li class="discussion list-group-item list-group-item-action color-font-grey " tid="<?php echo $t['tid']; ?>">
			<a class="stretched-link" href="view.php?id=<?php echo $t['tid']; ?>">
				<span class="text-title color-font-default"><?php echo base64_decode($t['title'], true) ? base64_decode($t['title'], true) : $t['title']; ?></span>
				<span class="text-date color-font-secondary date" date="<?php echo date('YmdHis', $t['pubtime']); ?>"></span>
				<span class="text-nickname color-font-<?php echo $t['type'] > 0 ? 'danger' : 'important'; ?>"><?php echo $t['name'] ? $t['name'] : "匿名"; ?></span>
				<span class="text-id color-font-<?php echo $t['type'] > 0 ? 'danger' : 'secondary'; ?>">ID:<?php echo $ID; ?></span>
				<br class="visible-xs visible-sm" />
				<p class="text-content color-font-default ">
					<?php
					echo base64_decode($t['content'], true) ? base64_decode($t['content'], true) : $t['content']; ?></p>

				<?php echo $t['SAGE'] == 0 ? '' : '<div class="sage color-font-danger"><i class="fa-solid fa-ban"></i>&nbsp;本讨论已经被下沉（<abbr  title="该讨论不会因为新回复而被顶到页首">?</abbr>）</div>' ?>
				<div class="latestReply color-theme "><i class="fa-solid fa-calendar-days"></i>&nbsp;最新回复时间：<span class="date" date="<?php echo date('YmdHis', $t['lastreptime']); ?>"></div>
			</a>
		</li>
	<?php } ?>
</ul>
<?php require('view/pagination.php'); ?>