<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php'); ?>
<div class="container mt-3">
	<div class="row">
		<div class="col-md-3">
			<div class="sticky-top">
				<?php include(ROOT . 'view/lmenu.php'); ?>
			</div>
		</div>
		<div class="col-md-9 main">
			<div class="shadow-sm p-3 color-background rounded mb-3">
				<h2 class="title color-font-default"><?php echo $curr_cat['cat_name']; ?></h2>
				<p class="intro color-font-default"><?php echo $curr_cat['intro']; ?></p>
				<hr class="discussionHr" />
				<ul class="list-group  list-group-flush">
					<?php
					if (count($threads) == 0) {
						if ($curr_cat['postable'] == 0 && $_SESSION['type'] < 1) {
							echo '<div class="notice color-font-default">虽然还没有人发起讨论，但是你也不能在这里说话~</div>';
						} else {
							echo '<div class="notice color-font-default">还没有人发起讨论，由你发起第一个讨论吧！</div>';
						}
					}
					foreach ($threads as $k => $t) { ?>
						<li class="discussion list-group-item list-group-item-action color-font-grey" tid="<?php echo $t['tid']; ?>">
							<div class="stretched-link d-flex justify-content-between" onclick="window.location.href='view.php?id=<?php echo $t['tid']; ?>'">
								<div class="contentsContainer">
									<div class="titleContainer d-flex flex-row align-items-end">
										<?php if (subscriptionModel::isSubscribed($t['tid'])) { ?>
											<div class="inline subscribeBtn badge color-badge-active">
												<i class="fa-solid fa-bookmark"></i>
											</div>
										<?php } ?>
										<span class="avatarSVG">
											<?php echo userModel::getCurrentAvatarSVG($t['name']);
											?>
										</span>
										<span class="d-flex flex-column inline-block ml-1 mr-1">
											<span class="d-flex flex-row">
												<span class="text-nickname color-font-secondary"><?php echo $t['name']; ?></span>
												<span class="text-date color-font-secondary small ml-1">发表在<?php echo $cat_name_array[$t['cat'] - 1]['cat_name']; ?> </span>
												<span class="text-date color-font-secondary small"> <i class="fa-regular fa-comment-dots"></i>&nbsp;<span class="date" date="<?php echo date('YmdHis', $t['lastreptime']); ?>"></span></span>

											</span>
											<span class="text-title color-font-default">
												<?php
												$indexTitle = base64_decode($t['title'], true) ?: $t['title'];
												echo $indexTitle; ?>
											</span>
										</span>
										<?php if ($t['SOLVED'] == 1) { ?>
											<span class="subscribeBtn badge badge-warning">
												<i class="fa-solid fa-check"></i>&nbsp;已解决
											</span>
										<?php } ?>
									</div>


									<div class="text-content color-font-default ">
										<?php
										$indexContent = base64_decode($t['content'], true) ?: $t['content'];
										$indexContent = str_replace('<div class="fandaoDoodle">', "", $indexContent);
										$indexContent = str_replace('</div>', "", $indexContent); //清理doodle										
										$indexContent = preg_replace('/upload\//', 'upload/thumbnail/', $indexContent);
										echo $indexContent;
										/* if (strlen($indexContent) <= CONTENT_LENGTH_LIMIT) {
											echo $indexContent;
										} else {
											echo mb_substr($indexContent, 0, CONTENT_LENGTH_LIMIT / 2) . "<b class='hideOverLimitText color-font-secondary'>......</b>";
										} */  ?></div>
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
				<?php require(ROOT . 'view/pagination.php') ?>
				<?php
				if (userModel::isLogin() && ($curr_cat['postable'] == 1 || $_SESSION['type'] > 0)) {
					/* start 判断当前是否是小说版，若是，则替换为不同的小说发表模板*/
					if ($curr_cat_id == 6) {
				?> <div class="col-md-12 d-flex justify-content-center mt-5 mb-5 ">
							<button class="btn color-button-primary btn-lg" type="button" onclick="window.location.href=('article.php?title=1')" title="开始创作"><i class="fa-solid fa-feather"></i>&nbsp;开始创作 <i class="fa-solid fa-heart"></i></button>

						</div>
					<?php
					} else {					?>

						<div class="d-none d-md-block">
							<div id="new-discuss">
								<p class="color-font-default"><i class="fa-solid fa-plus"></i>&nbsp;在<?php echo $curr_cat['cat_name'] ?>发起新的讨论</p>
								<form>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<label class="input-group-text textPrepend color-background-secondary color-font-default" for="discussTitle"><i class="fa-solid fa-quote-left"></i>&nbsp;标题 <i class="fa-solid fa-quote-right"></i></label>
										</div>
										<input type="text" class="form-control color-background-secondary color-font-default newDiscussTitle" id="discussTitle" name="title" maxlength="30">
									</div>
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

									<textarea class="color-background-secondary color-font-default newDiscussContent" name="content" id="content" cols="30" rows="7" placeholder="|∀` )"></textarea>
									<input type="hidden" class="newDiscussCatagory" name="cat" value="<?php echo $curr_cat_id; ?>">
									<input class="btn color-button-primary newDiscussBtn" type="button" value="发送" ifmodal="false">
								</form>
							</div>
						</div>
						<div class=" d-sm-block d-md-none ">
							<button class="btn  color-button-primary  color-button-primary" id="newMessageBtn" type="button" onclick="getModal()" title="发起新的讨论"><i class="fa-solid fa-plus"></i></button>
						</div>
						<!-- Modal -->
						<div class=" modal fade" id="replyBox" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="replyBoxLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content color-background" id="new-discuss">
									<div class="modal-header">
										<h5 class="modal-title color-font-default" id="replyBoxLabel"><i class="fa-solid fa-plus"></i>&nbsp;在<?php echo $curr_cat['cat_name'] ?>发起新的讨论</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
										</button>
									</div>
									<form>
										<div class="modal-body">
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<label class="input-group-text textPrepend color-background-secondary color-font-default" for="discussTitleModal"><i class="fa-solid fa-quote-left"></i>&nbsp;标题 <i class="fa-solid fa-quote-right"></i></label>
												</div>
												<input type="text" class="form-control color-background-secondary color-font-default newDiscussTitleModal" id="discussTitleModal" name="title" maxlength="30">
											</div>
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
											<textarea class="color-background-secondary color-font-default newDiscussContentModal" name="content" id="contentModal" cols="30" rows="7" placeholder="|∀` )"></textarea>
											<input type="hidden" class="newDiscussCatagoryModal" name="cat" value="<?php echo $curr_cat_id; ?>">
										</div>
										<div class="modal-footer">
											<input class="btn color-button-secondary" type="button" data-dismiss="modal" value="取消"></input>
											<input class="btn color-button-primary newDiscussBtnModal" type="button" value="发送" ifmodal="true"></input>
										</div>
									</form>
								</div>
							</div>
						</div>

				<?php }
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php include(ROOT . 'view/footer.php'); ?>