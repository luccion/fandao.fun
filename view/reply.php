<?php defined('ACC') || exit('ACC Denied');
function fontawesomeNumber($number)
{
    $n = str_split(strval($number));
    $fNumber = "";
    for ($i = 0; $i < sizeof($n); $i++) {
        if ($n[$i] == "-") {
            $fNumber = $fNumber . '<i class="fa-solid fa-minus"></i>';
        } else {
            $fNumber = $fNumber . '<i class="fa-solid fa-' . $n[$i] . '"></i>';
        }
    }

    return $fNumber;
}
?>
<div class="floorNumber color-font-secondary"><i class="fa-solid fa-hashtag"></i>
    <?php echo fontawesomeNumber($reply['floor']); ?>
</div>

<span class="text-date color-font-secondary"><?php echo date('Y-m-d H:i:s', $reply['reptime']); ?></span>
<span class="text-nickname color-font-important"><?php echo $reply['name']; ?></span>
<?php echo $reply['uid'] == $t['uid'] ? '<span class="text-po">(PO主)</span>' : ''; ?>

<br class=" visible-xs visible-sm" />
<p class="text-content color-font-default "><?php echo base64_decode($reply['content']); ?></p>
<?php if (userModel::isLogin()) { ?>
    <span class="text-link"><a class="badge  color-badge-default" onclick="getModal(<?php echo $t['tid'] ?>,<?php echo $reply['floor'] ?>)"><i class="fa-solid fa-comment-dots"></i>&nbsp;回复</a>
    </span>
<?php } ?>
<?php if (userModel::isLogin() && $_SESSION['type'] > 0) { ?>
    <span class="text-link"><a class="badge  color-badge-default" href="edit.php?tid=<?php echo $t['tid'] ?>&f=<?php echo $reply['floor'] ?>"><i class="fa-solid fa-pen"></i>&nbsp;编辑</a></span>
    <span class="text-link"><a class="badge  color-badge-default" onclick="if(!confirm('确实要删除吗?')){return false;};" href="delete.php?tid=<?php echo $t['tid'] ?>&f=<?php echo $reply['floor'] ?>"><i class="fa-solid fa-trash-can"></i>&nbsp;删除</a></span>
<?php } ?>