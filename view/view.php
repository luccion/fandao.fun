<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php');
if ($_SESSION['type'] == 4) {
	//print_r($threads);
}
?>
<div class="container mt-3">
	<div class="row">
		<div class="col-md-3">
			<div class="sticky-top">
				<?php include(ROOT . 'view/lmenu.php'); ?>
			</div>
		</div>
		<div class="col-md-9 main">
			<div class="main shadow-sm p-3 color-background rounded mb-3">
				<?php
				foreach ($threads as $k => $t) {
				?>
					<div class="discussionTitle color-background ">
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-title color-font-default discussionTitle color-background Text">
								<?php
								echo base64_decode($t['title'], true) ?: $t['title'];
								?><span class="text-date color-font-secondary small ml-1"><?php echo $cat_name_array[$t['cat'] - 1]['cat_name']; ?></span></span>

							<?php if (userModel::isLogin()) {
								/* start 判断当前是否是小说版，是否是作者，若皆是，作者回复时将有不一样的回复界面 */
								if (($curr_cat_id == 6 && ($t['uid']) == $_SESSION['uid'])) { ?>
									<span class="text-link"><a class="viewBtn btn color-badge-default" href="article.php?article=<?php echo $t['tid']; ?>"><i class="fa-solid fa-comment-dots"></i>&nbsp;继续</a></span>
								<?php } else { ?>
									<span class="text-link"><a class="viewBtn btn color-badge-default" onclick="getModal()"><i class="fa-solid fa-comment-dots"></i>&nbsp;回复</a></span>
							<?php
								}
								/* end */
							} ?>
						</div>
						<hr class="discussionHr" />
					</div>
					<div class="discussion" tid="<?php echo $t['tid']; ?>">
						<div class="floorNumber color-font-secondary"><i class="fa-solid fa-hashtag"></i>
							<?php echo fontawesomeNumber(0); ?>
						</div>
						<span class="avatarSVG">
							<?php echo userModel::getCurrentAvatarSVG($t['name']);
							?></span>
						<span class="text-nickname color-font-secondary normal"><?php echo $t['name'] ?: "匿名"; ?></span>
						<span class="text-nickname color-font-important"><?php echo $t['uid'] == $_SESSION['uid'] ? "(我)" : ""; ?></span>
						<span class="text-date color-font-secondary date" date="<?php echo date('YmdHis', $t['pubtime']); ?>"></span>
						<div class="text-content color-font-default ">
							<?php
							$indexContent = base64_decode($t['content'], true) ?: $t['content'];
							echo $indexContent; ?>
						</div>
						<?php echo $t['SAGE'] == 0 ? '' : '<div class="sage color-font-danger"><i class="fa-solid fa-ban"></i>&nbsp;本讨论已经被下沉（<abbr title="该讨论不会因为新回复而被顶到页首">?</abbr>）</div>' ?>
						<div class="text-center">
							<?php
							if ($t['contribution'] > 0) { ?>
								<div class="color-background-grey color-font-default shadow view-contribution" data-toggle="modal" data-target="#contributeModal">POPO收到了<? echo $t['contribution'] ?>x🍟打赏</div>
							<? }

							if (userModel::isLogin()) { ?>
								<span class="text-link">
									<a class="viewBtn likeBtn btn <?php echo threadlikesModel::isLiked($t['tid']) ? 'color-badge-active' : 'color-badge-default' ?>" clicked="<?php echo threadlikesModel::isLiked($t['tid']) ? '1' : '0' ?>">
										<i class="fa-solid fa-thumbs-up"></i><?php echo $reply_num = $msg->countLikes($t['tid']); ?>
									</a>
								</span>
								<span class="text-link">
									<a class="viewBtn subscribeBtn btn <?php echo subscriptionModel::isSubscribed($t['tid']) ? 'color-badge-active' : 'color-badge-default' ?>" clicked="<?php echo subscriptionModel::isSubscribed($t['tid']) ? '1' : '0' ?>">
										<?php echo subscriptionModel::isSubscribed($t['tid']) ? '<i class="fa-solid fa-xmark"></i>&nbsp;取消订阅' : '<i class="fa-solid fa-bookmark"></i>&nbsp;订阅'; ?>
									</a>
								</span>

								<?php
								if ($_SESSION['uid'] != $t['uid']) { ?>
									<span class="text-link"><a class="viewBtn btn color-badge-default " data-toggle="modal" data-target="#contributeModal">🍟整点薯条</a></span>
							<?php }
							} ?>
							<?php if (userModel::isLogin() && $_SESSION['type'] > 0) { ?>
								<span class="text-link"><a class="viewBtn btn color-badge-default" href="edit.php?tid=<?php echo $t['tid'] ?>"><i class="fa-solid fa-pen"></i>&nbsp;编辑</a></span>
								<span class="text-link"><a class="viewBtn btn badge-warning" onclick="if(!confirm('要<?php echo $t['SAGE'] == 1 ? '解除' : ''; ?>下沉吗?')){return false;};" href="sage.php?tid=<?php echo $t['tid'] ?>"><?php echo $t['SAGE'] == 1 ? '解除' : ''; ?><i class="fa-solid fa-ban"></i>&nbsp;下沉</a></span>
								<span class="text-link"><a class="viewBtn btn badge-danger" onclick="if(!confirm('确实要删除吗?')){return false;};" href="delete.php?tid=<?php echo $t['tid'] ?>"><i class="fa-solid fa-trash-can"></i>&nbsp;删除</a></span>
							<?php }		?>
						</div>
						<?php
						if (sizeof($replies[$k]) >= 1) { ?>
							<hr class="discussionHr" />
						<?php } ?>
						<ul class="list-group list-group-flush">
							<?php
							foreach ($replies[$k] as $key => $value) {
							?>
								<li class="reply list-group-item" floor=<?php echo $value['floor']; ?>>
									<div class="d-flex flex-row justify-content-between">
										<div>
											<span class="floorNumber color-font-secondary"><i class="fa-solid fa-hashtag"></i>
												<?php echo fontawesomeNumber($value['floor']); ?>
											</span>
											<span class="avatarSVG">
												<?php echo userModel::getCurrentAvatarSVG($value['name']);
												?></span>
											<span class="text-nickname color-font-secondary normal"><?php echo $value['name'] ?: "匿名"; ?></span>
											<?php echo $value['name'] == $t['name'] ? '<span class="text-po small">(PO主)</span>' : ''; ?>
											<span class="text-date color-font-secondary date" date="<?php echo date('YmdHis', $value['reptime']); ?>"></span>
										</div>
										<div>
											<?php if (userModel::isLogin()) { ?>
												<span class="text-link"><a class="badge color-badge-default" onclick="getModal(<?php echo $t['tid'] ?>,<?php echo $value['floor'] ?>)"><i class="fa-solid fa-comment-dots"></i>&nbsp;回复</a>
												</span>
											<?php } ?>
											<?php if (userModel::isLogin() && $_SESSION['type'] > 0) { ?>
												<span class="text-link"><a class="badge color-badge-default" href="edit.php?tid=<?php echo $t['tid'] ?>&f=<?php echo $value['floor'] ?>"><i class="fa-solid fa-pen"></i>&nbsp;编辑</a></span>
												<span class="text-link"><a class="badge color-badge-default" onclick="if(!confirm('确实要删除吗?')){return false;};" href="delete.php?tid=<?php echo $t['tid'] ?>&f=<?php echo $value['floor'] ?>"><i class="fa-solid fa-trash-can"></i>&nbsp;删除</a></span>

											<?php } ?>
										</div>
									</div>
									<div class="text-content color-font-default ">
										<?php
										$replyContent = base64_decode($value['content'], true) ?: $value['content'];
										echo $replyContent; ?>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>

				<?php } ?>
				<?php require(ROOT . 'view/pagination.php') ?>

				<?php if (userModel::isLogin()) { ?>
					<div class="d-none d-md-block">
						<div id="new-discuss">
							<p class="color-font-default"><i class="fa-solid fa-comment-dots"></i>&nbsp;发出回复</p>
							<form>
								<div class="typeWidget input-group mb-3">
									<div class="input-group-prepend">
										<label class="input-group-text textPrepend color-background-secondary color-font-default" for="textface"><i class="fa-solid fa-chevron-left"></i>&nbsp;颜文字 <i class="fa-solid fa-chevron-right"></i></label>
									</div>
									<select class="custom-select color-background-secondary color-font-default" id="textface" title="textface">
										<?php include(ROOT . 'view/textface.html'); ?>
									</select>
									<div class="input-group-append">
										<button class="btn color-background-secondary color-font-default" id="addLinkBtn" type="button"><i class="fa-solid fa-link"></i>&nbsp;链接</button>
									</div>
									<div class="input-group-append">
										<button class="btn color-background-secondary color-font-default" id="addDice" type="button"><i class="fa-solid fa-dice"></i>&nbsp;骰子</button>
									</div>
									<div class="input-group-append">
										<button class="btn color-background-secondary color-font-default" type="button" data-toggle="modal" data-target="#painter"><i class="fa-solid fa-paintbrush"></i>&nbsp;绘图板</button>
									</div>
								</div>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<label class="input-group-text color-background-secondary color-font-default" for="fileUploader"><i class="fa-solid fa-image"></i></label>
									</div>
									<div class="custom-file">
										<input name="file" type="file" class="custom-file-input color-background-secondary color-font-default" id="fileUploader" aria-describedby="inputGroupFileAddon03">
										<label class="custom-file-label color-background-secondary color-font-secondary" for="fileUploader"><i class="fa-solid fa-circle-exclamation"></i>&nbsp;请选择小于5MB的图片并上传</label>
									</div>
									<div class="input-group-append">
										<button class="btn color-background-secondary color-font-default" type="button" id="uploadIMG"><i class="fa-solid fa-upload"></i>&nbsp;上传图片</button>
									</div>
								</div>
								<textarea class="color-background-secondary color-font-default newReplyContent" name="content" id="content" cols="30" rows="7" placeholder="|∀` )"><?php echo isset($_GET['reply']) ? strip_tags($_GET['reply']) : ''; ?></textarea>
								<input class="newReplyTid" type="hidden" name="tid" value="<?php echo $threads[0]['tid']; ?>">
								<input class="btn color-button-primary newReplyBtn" type="button" value="发送"><br>
							</form>
						</div>
					</div>
					<div class=" d-sm-block d-md-none ">
						<button class="btn color-button-primary color-button-primary" id="newMessageBtn" type="button" onclick="getModal()" title="发出回复"><i class="fa-solid fa-comment-dots"></i></button>
					</div>
					<!-- Modal -->
					<div class=" modal fade" id="replyBox" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="replyBoxLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content color-background" id="new-discuss">
								<div class="modal-header">
									<h5 class="modal-title color-font-default" id="replyBoxLabel"><i class="fa-solid fa-comment-dots"></i>&nbsp;发出回复</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
									</button>
								</div>
								<form>
									<div class="modal-body">
										<div class="typeWidget input-group mb-3">
											<div class="input-group-prepend">
												<label class="input-group-text textPrepend color-background-secondary color-font-default" for="textfaceModal"><i class="fa-solid fa-chevron-left"></i>&nbsp;颜文字 <i class="fa-solid fa-chevron-right"></i></label>
											</div>
											<select class="custom-select color-background-secondary color-font-default" id="textfaceModal" title="textface">
												<?php include(ROOT . 'view/textface.html'); ?>
											</select>
											<div class="input-group-append">
												<button class="btn color-background-secondary color-font-default" id="addLinkBtnModal" type="button"><i class="fa-solid fa-link"></i></button>
											</div>
											<div class="input-group-append">
												<button class="btn color-background-secondary color-font-default" id="addDiceModal" type="button"><i class="fa-solid fa-dice"></i></button>
											</div>
											<div class="input-group-append">
												<button class="btn color-background-secondary color-font-default" type="button" data-toggle="modal" data-target="#painter"><i class="fa-solid fa-paintbrush"></i></button>
											</div>
										</div>
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<label class="input-group-text color-background-secondary color-font-default" for="fileUploaderModal"><i class="fa-solid fa-image"></i></label>
											</div>
											<div class="custom-file">
												<input name="file" type="file" class="custom-file-input color-background-secondary color-font-default" id="fileUploaderModal" aria-describedby="inputGroupFileAddon03">
												<label class="custom-file-label color-background-secondary color-font-secondary lableModal" for="fileUploaderModal"><i class="fa-solid fa-circle-exclamation"></i>&nbsp;请选择小于5MB的图片并上传</label>
											</div>
											<div class="input-group-append">
												<button class="btn color-background-secondary color-font-default" type="button" id="uploadIMGModal"><i class="fa-solid fa-upload"></i>&nbsp;上传图片</button>
											</div>
										</div>
										<textarea class="color-background-secondary color-font-default newReplyContentModal" name="content" id="contentModal" cols="30" rows="7" placeholder="|∀` )"></textarea>
										<input class="newReplyTidModal" type="hidden" name="tid" value="<?php echo $threads[0]['tid']; ?>">
									</div>
									<div class="modal-footer">
										<input class="btn color-button-secondary" type="button" data-dismiss="modal" value="取消"></input>
										<input class="btn color-button-primary newReplyBtnModal" type="button" value="发送"></input>
									</div>
								</form>
							</div>
						</div>
					</div>

					<!-- Modal -->
					<div class="modal fade" id="contributeModal" tabindex="-1" aria-labelledby="contributeLabel" aria-hidden="true" pouid="<?php echo $t['uid'] ?>" poavatar="<?php echo $t['name'] ?>" tid="<?php echo $t['tid'] ?>">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content color-background">
								<div class="modal-header">
									<h5 class="modal-title color-font-default" id="contributeLabel"><i class="fa-solid fa-hands-clapping fa-flip-horizontal"></i><i class="fa-solid fa-child-reaching"></i><i class="fa-solid fa-hands-clapping"></i>&nbsp;整点薯条！</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
									</button>
								</div>
								<div class="modal-body">
									<div class="color-font-default">给<?php echo $t['name'] ?>整点薯条，POPO会得到以下收益：</div>
									<div class="d-flex">
										<label class="card-radio-btn card-contribution">
											<input type="radio" name="contribution" class="card-input-element d-none" checked="" value="1">
											<div class="card card-body color-background">
												<span class="content_head color-font-default"><?php echo fontawesomeNumber(1); ?>&nbsp;🍟</span>
												<div class="content_sub color-font-grey"></div>
											</div>
										</label>
										<label class="card-radio-btn card-contribution">
											<input type="radio" name="contribution" class="card-input-element d-none" checked="" value="5">
											<div class="card card-body color-background">
												<span class="content_head color-font-default"><?php echo fontawesomeNumber(5); ?>&nbsp;🍟</span>
												<div class="content_sub color-font-grey"></div>
											</div>
										</label>
										<label class="card-radio-btn card-contribution">
											<input type="radio" name="contribution" class="card-input-element d-none" checked="" value="10">
											<div class="card card-body color-background">
												<span class="content_head color-font-default"><?php echo fontawesomeNumber(10); ?>&nbsp;🍟</span>
												<div class="content_sub color-font-grey"></div>
											</div>
										</label>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn color-button-secondary" data-dismiss="modal">还是算了</button>
									<button type="button" class="btn color-button-primary" id="contributionBtn"> 整点薯条！</button>
								</div>
							</div>
						</div>
					</div>

				<?php
				} ?>
			</div>
		</div>
	</div>
</div>

<?php include(ROOT . 'view/footer.php'); ?>