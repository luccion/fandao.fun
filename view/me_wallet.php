<?php defined('ACC') || exit('ACC Denied');
if ($_SESSION['email_verified']) {
?>
	<?php if (!$_SESSION['checkin']) { ?>
		<button class="btn btn-block color-button-primary" id="daily-checkin"><i class="fa-solid fa-user-check"></i>&nbsp;签到奖励
			+10🍟</button>
		</div>
		<div class="my-wallet main shadow-sm p-3 color-background rounded mb-3">
		<?php } ?>

		<form class="form-group">
			<br>
			<h3 class="color-font-default d-flex justify-content-between">
				<div><b><i class="fa-solid fa-wallet"></i>&nbsp;我的钱包 </b></div>
				<div class="balanceValue" id="balance-value" money="<?php echo $money ?>">
					🍟<b><?php echo $money ?></b>
				</div>
			</h3>
			<div id="uinfo" class="alert alert-info">输入对方的钱包地址以转账</div>
			<div class="form-row">
				<div class="col-md-6 mb-3">
					<label class="color-font-default" for="w"><i class="fa-solid fa-code-commit"></i>&nbsp;地址</label>
					<input id="w" type="text" class="form-control color-background-secondary user-select-all" name="wallet" value="<?php echo  $_SESSION['wallet']; ?>" maxlength="50" type="text" disabled>
				</div>

				<div class="col-md-6 mb-3">
					<label class="color-font-default" for="openid"><i class="fa-solid fa-eye" data-toggle='tooltip' data-placement='left' title='输入openid' id='openidSelector'></i>/&nbsp;物品</label>
					<select class="custom-select color-background-secondary color-font-default" id="selectedItem">
						<option value='cd7cbfea7e382773a22bc01872e3d3ff' selected>🍟薯条</option>
						<?php
						foreach ($property as $p) {
							switch ($p['type']) {
								case 3:
									$displayName = '🧾' . $p['chinese'];
									break;
								case 4:
									$displayName = '👩🏻‍🦰' . $p['chinese'];
									break;
								case 5:
									$displayName = '🟩' . $p['title'] . ' x ' . $p['amount'];
									break;
								case 8:
									$displayName = '🌐' . $p['chinese'];
									break;
							}
							if (($_SESSION['current_aid'] == $p['aid']) || ($p['onsale'] == true)) {
								continue;
							}
							echo '<option value="' . transferModel::gid2openid($p['gid']) . '">' .	$displayName . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-6 mb-3">
					<label class="color-font-default" for="toWallet"><i class="fa-solid fa-wallet"></i><i class="fa-solid fa-arrow-right-long"></i><i class="fa-solid fa-wallet"></i>&nbsp;对方地址</label>
					<input id="toWallet" type="text" class="form-control color-background-secondary" name="toWallet" value="" maxlength="50" type="text">
				</div>
				<div class="col-md-6 mb-3">
					<label class="color-font-default" for="amount"><i class="fa-solid fa-calculator"></i>&nbsp;数额</label>
					<input id="amount" type="number" class="form-control color-background-secondary" name="amount" value="" maxlength="50" type="text">
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-12 mb-3">
					<label class="color-font-default" for="note"><i class="fa-solid fa-file-pen"></i>&nbsp;备注</label>
					<input id="note" type="text" class="form-control color-background-secondary" name="note" value="" maxlength="64" type="text" placeholder="长度不超过64个字符">
				</div>
			</div>
			<div class="form-group">
				<button class="btn color-button-primary" id="confirmTransfer" type="button">确认转账</button>
			</div>
		</form>
		</div>
		<div class="main shadow-sm p-3 color-background rounded mb-3">
			<h3 class="color-font-default d-flex justify-content-between">
				<div><b><i class="fa-solid fa-arrow-down-up-across-line"></i>&nbsp;转账记录 </b></div>
			</h3>
			<div class="table-responsive">
				<table class="table table-striped mt-3 transfer-log">
					<tbody>
						<?php $transfer_log = transferModel::transfer_log_get_all($_SESSION['wallet']);
						rsort($transfer_log);
						foreach ($transfer_log as $tle) {
						?>
							<tr class="color-font-default">

								<?php
								$transferNote = base64_decode($tle['note']);
								if (substr($transferNote, 0, 13) == "contribution@") {
									$position = strpos($transferNote, "@|@");
									$threadTitle = substr($transferNote, $position + 3);
									if ($position) {
										$tle['toWallet'] = substr($transferNote, 13, $position - 13);
										$tle['note'] = base64_encode('🍟“' . $threadTitle . "”");
									} else {
										$tle['toWallet'] = substr($transferNote, 13);
										$tle['note'] = base64_encode("赞赏");
									}
								} else if ($transferNote == "reg_nft" && $tle["fromWallet"] == ("avatar" || "GENESIS")) {
									$tle['note'] = base64_encode("注册化身");
								} else if ($transferNote == "REGARTICLE" && $tle["fromWallet"] == ("ip")) {
									$tle['note'] = base64_encode("注册作品");
								} else if ($transferNote == "BUYTICKET" && $tle["toWallet"] == ("lottery")) {
									$tle['note'] = base64_encode("购买彩票");
								} else if ($transferNote == "TRADENFT") {
									$tle['note'] = base64_encode("交易资产");
								} else if ($transferNote == "WIKISCORE") {
									$tle['note'] = base64_encode("知识库贡献分数转化UCT");
								} else if ($transferNote == "DECOMPOSE" && $tle["fromWallet"] == ("fandao")) {
									$tle['note'] = base64_encode("资产碎片");
								} else if ($transferNote == "DECOMPOSE" && $tle["toWallet"] == ("BLACKHOLE")) {
									$tle['note'] = base64_encode("分解资产");
								} else if ($transferNote == "MINTUCT" && $tle["fromWallet"] == ("fandao")) {
									$tle['note'] = base64_encode("铸造UCT");
								} else if ($transferNote == "ISSUED" && $tle["fromWallet"] == ("ISSUED")) {
									$tle['note'] = base64_encode("发行");
								} else if (substr($transferNote, 0, 8) == "lottery@") {
									switch ($transferNote) {
										case "lottery@0":
											$tle['note'] = base64_encode("特等奖！！！");
											break;
										case "lottery@1":
											$tle['note'] = base64_encode("一等奖！！");
											break;
										case "lottery@2":
											$tle['note'] = base64_encode("二等奖！");
											break;
										case "lottery@3":
											$tle['note'] = base64_encode("安慰奖~");
											break;
										case "lottery@4":
											$tle['note'] = base64_encode("海鸥！");
											break;
									}
								}
								switch ($tle['type']) {
									case 0: // 1=>user,
										break;
									case 1: // 1=>currency,
										break;
									case 2: // 2=>stock,
										$tle['amount'] = '<i class="fa-solid fa-handshake-angle"></i>';
										break;
									case 3: // 3=>article,    	
										$tle['amount'] = '<i class="fa-solid fa-paintbrush"></i>';
										break;
									case 4: // 4=>avatar,	
										$tle['amount'] = '<i class="fa-solid fa-person-rays"></i>';
										break;
									case 5: // 5=>item,
										$tle['amount'] = '<span data-toggle="tooltip" data-placement="bottom" title="' . transferModel::get_abstract($tle['gid']) . '"><i class="fa-solid fa-puzzle-piece"></i>&nbsp;' . (int)$tle['amount'] . '</span>';
										break;
									case 6: // 6=>equipment,
										$tle['amount'] = '<i class="fa-solid fa-shirt"></i>';
										break;
									case 7: // 7=>artifact  
										$tle['amount'] = '<i class="fa-regular fa-gem"></i>';
										break;
									case 8: // 8=>planet  
										$tle['amount'] = '<i class="fa-solid fa-earth-europe"></i>';
										break;
								}
								$toWallet = "";
								$fromWallet = "";
								switch ($tle['fromWallet']) {
									case "avatar":
										$fromWallet = '<i class="fa-solid fa-landmark"></i>&nbsp;饭岛化身管理局';
										break;
									case "fandao":
										$fromWallet = '<i class="fa-solid fa-landmark"></i>&nbsp;饭岛基金会';
										break;
									case "ip":
										$fromWallet = '<i class="fa-solid fa-landmark"></i>&nbsp;饭岛知识产权管理局';
										break;
									case "lottery":
										$fromWallet = '<i class="fa-solid fa-ticket"></i>&nbsp;饭岛海鸥彩票基金会';
										break;
									case "BLACKHOLE":
										$fromWallet = '<i class="fa-solid fa-arrows-to-dot"></i>&nbsp;黑洞';
										break;
									case "ISSUED":
										$fromWallet = '<i class="fa-solid fa-coins"></i>&nbsp;饭岛铸币厂';
										break;
								}
								switch ($tle['toWallet']) {
									case "avatar":
										$toWallet = '<i class="fa-solid fa-landmark"></i>&nbsp;饭岛化身管理局';
										break;
									case "fandao":
										$toWallet = '<i class="fa-solid fa-landmark"></i>&nbsp;饭岛基金会';
										break;
									case "ip":
										$toWallet = '<i class="fa-solid fa-landmark"></i>&nbsp;饭岛知识产权管理局';
										break;
									case "lottery":
										$toWallet = '<i class="fa-solid fa-ticket"></i>&nbsp;饭岛海鸥彩票基金会';
										break;
									case "BLACKHOLE":
										$toWallet = '<i class="fa-solid fa-arrows-to-dot"></i>&nbsp;黑洞';
										break;
									case "ISSUED":
										$toWallet = '<i class="fa-solid fa-coins"></i>&nbsp;饭岛铸币厂';
										break;
								}
								if ($tle['fromWallet'] == $_SESSION['wallet']) {
								?>
									<td class="tabelIcon color-font-danger"><i class="fa-solid fa-right-long"></i></td>

									<td class="tableAmount"><?php echo $tle['amount'] ?></td>
									<td class="tableWallet"><?php echo $toWallet ?: $tle['toWallet'] ?></td>
								<?php } else { ?>
									<td class="tabelIcon color-font-important"><i class="fa-solid fa-left-long"></i></td>
									<td class="tableAmount"><?php echo $tle['amount'] ?></td>
									<td class="tableWallet"><?php echo $fromWallet ?: $tle['fromWallet'] ?></td>
								<?php } ?>
								<td class="tableNote"><?php echo base64_decode($tle['note']) ?></td>
								<td class="tableTime date-to" date="<?php echo date('YmdHis', $tle['time']) ?>"></td>
							</tr>
						<?php
							$toWallet = "";
							$fromWallet = "";
						}
						?>

					</tbody>
				</table>
			</div>
		</div>

	<?php } else { ?>
		<h2><i class="fa-solid fa-face-frown"></i>&nbsp;因为没有验证邮箱所以不能使用钱包功能~</h2>
		<small>前往<strong>信息设定</strong>以验证邮箱<br></small>
		</div>
	<?php } ?>