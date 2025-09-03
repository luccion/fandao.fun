<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php'); ?>
<div class="container mt-3">

    <div class="row">
        <div class="col-md-12 main">
            <div class="main">
                <div class="col-md-12">
                    <h2 class="title color-font-default"><i class="fa-solid fa-landmark"></i>&nbsp;饭岛政务大厅</h2>
                    <p class="intro color-font-default">一个岛民讨论岛内建设决议的地方，是白宙城计划的试验田。<br>这是不记名投票，每次投票或者切换投票都会消耗 1x🍟。</p>
                </div>

                <div class="row">
                    <?php
                    for ($i = 0; $i < sizeof($proposal); $i++) {
                    ?>
                    <div class="card col m-1 shadow-sm color-background rounded mb-3">
                        <div class="card-body card-hall" pid="<?php echo $pid = $proposal[$i]['pid'] ?>">

                            <h5 class="card-title color-font-default"><i
                                    class="fa-solid fa-check-to-slot"></i>&nbsp;<?php echo base64_decode($proposal[$i]['title'], true) ?: $proposal[$i]['title']; ?>
                            </h5>
                            <p class="card-text color-font-default">
                                <?php echo base64_decode($proposal[$i]['content'], true) ?: $proposal[$i]['content']; ?>
                            </p>
                            <?php if ($proposal[$i]['uid'] == $_SESSION['uid']) { ?>
                            <div class="myProposal color-button-primary-ticket">我发起的决议</div>
                            <?php } ?>
                            <?php if ($proposal[$i]['passed']) { ?>
                            <div class="cardPassed color-font-default"><i class="fa-solid fa-circle-check"></i></div>
                            <?php } else if ($proposal[$i]['rejected']) { ?>
                            <div class="cardPassed color-font-default"><i class="fa-solid fa-circle-xmark"></i></div>
                            <?php } else { ?>
                            <div>
                                <div class="btn-group mt-2" role="group">
                                    <button type="button"
                                        class="btnButton btn color-button-<?php echo proposalModel::isVotedPro($pid) ? "primary" : "secondary" ?> btnVote proposalVoteBtn"
                                        voteFor="1" clicked="<?php echo proposalModel::isVotedPro($pid) ? 1 : 0 ?>"><i
                                            class="fa-solid fa-thumbs-up"></i></button>
                                    <button type="button"
                                        class="btnNumber btn color-button-<?php echo proposalModel::isVotedPro($pid) ? "primary" : "secondary" ?>"
                                        disabled><?php echo $num = $msg->countPros($pid); ?></button>
                                </div>
                                <div class="btn-group mt-2" role="group">
                                    <button type="button"
                                        class="btnNumber btn  color-button-<?php echo proposalModel::isVotedCon($pid) ? "danger" : "secondary" ?>"
                                        disabled><?php echo $msg->countCons($pid); ?></button>
                                    <button type="button"
                                        class="btnButton btn  color-button-<?php echo proposalModel::isVotedCon($pid) ? "danger" : "secondary" ?> btnVote proposalVoteBtn"
                                        voteFor="0" clicked="<?php echo proposalModel::isVotedCon($pid) ? 1 : 0 ?>"><i
                                            class="fa-solid fa-thumbs-down fa-flip-horizontal"></i></button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>

                        <?php if (userModel::isLogin() && $_SESSION['type'] > 0) { ?>
                        <div class="dropdown cardDropDown">
                            <a class="dropdown-toggle color-font-default" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-expanded="false"></a>
                            <div class="dropdown-menu dropdown-menu-right color-background-secondary color-font-default"
                                aria-labelledby="dropdownMenuLink" pid="<?php echo $pid = $proposal[$i]['pid'] ?>">
                                <button class="dropdown-item color-font-default deleteProposalBtn"><i
                                        class="fa-solid fa-trash-can"></i>&nbsp;删除</button>
                                <button class="dropdown-item color-font-default"><i
                                        class="fa-solid fa-square-check"></i>&nbsp;通过</button>
                                <button class="dropdown-item color-font-default"><i
                                        class="fa-solid fa-square-xmark"></i>&nbsp;否决</button>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    <?php
                        $rows = 1; //希望有几列
                        if (($i - 1) % $rows == 0) {
                            echo '</div><div class="row">';
                        }
                    } ?>
                </div>

            </div>
        </div>
    </div>

</div>

<?php
if (userModel::isLogin()) { ?>
<!-- Modal -->
<div class=" modal fade" id="replyBox" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="replyBoxLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content color-background" id="new-discuss">
            <div class="modal-header">
                <h5 class="modal-title color-font-default" id="replyBoxLabel"><i
                        class="fa-solid fa-plus"></i>&nbsp;创建新的决议 <i class="fa-solid fa-check-to-slot"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="input-group mb-3">

                        <div class="input-group-prepend">
                            <label class="input-group-text textPrepend color-background-secondary color-font-default"
                                for="newProposalTitle">
                                <i class="fa-solid fa-quote-left"></i>&nbsp;标题 <i class="fa-solid fa-quote-right"></i>
                            </label>
                        </div>
                        <input type="text"
                            class="form-control color-background-secondary color-font-default newProposalTitle"
                            id="newProposalTitle" name="title" maxlength="30">
                    </div>
                    <textarea class="color-background-secondary color-font-default newProposalContent" name="content"
                        id="newProposalContent" cols="30" rows="7"></textarea>
                    <input type="hidden" class="newDiscussCatagoryModal" name="cat" value="<?php echo $curr_cat_id; ?>">
                </div>
                <div class="modal-footer">
                    <input class="btn color-button-secondary" type="button" data-dismiss="modal" value="取消"></input>
                    <button class="btn color-button-primary newProposalBtn" type="button" ifmodal="true">创建 (🍟
                        -10)</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>


<div class="col-md-12 d-flex justify-content-center mt-5 mb-5">
    <button class="btn color-button-primary btn-lg" type="button" onclick="getModal()" title="创建新的决议"><i
            class="fa-solid fa-plus"></i>&nbsp;创建决议 <i class="fa-solid fa-check-to-slot"></i></button>
</div>

</div>
<?php include(ROOT . 'view/footer.php'); ?>
<script>
$(document).ready(function() {
    $("#menuBtn").remove();
});
</script>