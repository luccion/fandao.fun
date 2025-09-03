<?php defined('ACC') || exit('ACC Denied'); ?>
<span class="text-title color-font-default"><?php echo base64_decode($t['title']); ?></span>
<span class="text-nickname color-font-important"><?php echo $t['name']; ?></span>
<span class="text-date color-font-secondary"><?php echo date('Y-m-d H:i:s', $t['pubtime']); ?></span>
<span class="color-font-<?php echo $t['type'] > 0 ? 'danger' : 'secondary'; ?>"><?php echo 'ID:', $t['tid']; ?></span>
<br class="visible-xs visible-sm" />
<p class="text-content color-font-default "><?php echo base64_decode($t['content']); ?></p>