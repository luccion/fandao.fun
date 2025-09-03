<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php');


if ($title == 1) {
?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12 main m-1 shadow-sm color-background rounded p-2 color-font-default">
                <div id="newArticle">
                    <div class="input-group mb-1">
                        <input type="text" class="form-control color-background-secondary color-font-default articleTitle" id="articleTitle" name="title" maxlength="30" placeholder="在此输入标题">
                    </div>
                    <div id="imananchor"></div>
                    <div class="color-background-secondary color-font-default articleContent p-2" name="content" id="articleContent" cols="30" rows="20" placeholder="在此输入正文" contenteditable="true" spellcheck="false"></div>
                    <input type="hidden" class="newDiscussCatagory" name="cat" value="6">
                </div>
            </div>
        </div>
    </div>
<?php } else {
?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12 main m-1 shadow-sm color-background rounded p-2 color-font-default">
                <div id="newArticle">
                    <div id="imananchor"></div>
                    <div class="color-background-secondary color-font-default articleContent p-2" name="content" id="articleContent" cols="30" rows="20" placeholder="在此输入正文" contenteditable="true" spellcheck="false"></div>
                </div>
            </div>
        </div>
    </div>
<?php }
?>
<div class="bottomToolbar color-background color-font-default">
    <div class="wordCounter">字数统计：0</div>
    <div class="gutterLine"></div>
    <div class="pauseTide" data-toggle="tooltip" data-placement="top" title="只是让背景的浪暂停"><i class="fa-regular fa-circle-pause"></i></div>
    <div class="gutterLine"></div>
    <div class="color-font-default fontSizeControllerContainer">
        <div class="fontSizeController fontMinify"><i class="fa-regular fa-square-minus"></i></div>
        <div class="fontSizeViewer"></div>
        <div class="fontSizeController fontMagnify"><i class="fa-regular fa-square-plus"></i></div>
    </div>
    <div class="gutterLine"></div>
    <input type="checkbox" class="switchLight" id="swithLight" <?php switch ($_COOKIE["theme"]) {

                                                                    case 'DARKMODE':
                                                                        echo "checked='false'";
                                                                        break;
                                                                    case 'LIGHTMODE':
                                                                        echo "checked='true'";
                                                                        break;
                                                                    default:
                                                                        echo "checked = 'true'";
                                                                }  ?>>
    <div class="gutterLine"></div>
    <div class="question" data-toggle="tooltip" data-placement="top" title="祝你码字快乐(・ω・)"><i class="fa-regular fa-circle-question"></i></div>

</div>


</div>
<?php include(ROOT . 'view/footer.php'); ?>
<script>
    $(document).ready(function() {
        $("#menuBtn").remove();
        $(".navbar").css({
            'justify-content': 'space-between'
        });

        const explorer = window.navigator.userAgent;
        if (explorer.indexOf("Firefox") >= 0) {
            $("div").remove("#articleContent");
            $("#imananchor").after('<textarea class="color-background-secondary color-font-default articleContent p-2" name="content" id="articleContent" cols="30" rows="20" placeholder="在此输入正文"></textarea>');
        }

        <?php if ($title == 1) { ?>
            $(".navbar").append('<input class="btn color-button-primary newArticleBtn" type="button" value="发表">');
        <?php
        } else {
        ?> $(".navbar").append('<input class="btn color-button-primary continueArticleBtn" type="button" value="发表">');
        <?php  } ?>
        var animState = 1;
        $(".pauseTide").click(function() {
            if (animState == 1) {
                $("#ocean .parallax>use").removeClass("running");
                $("#ocean .parallax>use").addClass("paused");
                $(".pauseTide").html('<i class="fa-regular fa-circle-play"></i>');
                animState = 0;
            } else {
                $("#ocean .parallax>use").removeClass("paused");
                $("#ocean .parallax>use").addClass("running");
                $(".pauseTide").html('<i class="fa-regular fa-circle-pause"></i>');
                animState = 1;
            }
        })
        let articleFontSize = parseInt($(".articleContent").css('font-size'));
        $(".fontSizeViewer").html(articleFontSize + "px");
        $(".fontMinify").click(() => {
            let articleFontSize = parseInt($(".articleContent").css('font-size'));
            if (articleFontSize <= 16) {
                return;
            } else {
                articleFontSize = (articleFontSize - 8) + "px";
                $(".articleContent").css({
                    'font-size': articleFontSize
                });
                $(".fontSizeViewer").html(articleFontSize);
            }
        })
        $(".fontMagnify").click(() => {
            let articleFontSize = parseInt($(".articleContent").css('font-size'));
            if (articleFontSize >= 64) {
                return;
            } else {
                articleFontSize = (articleFontSize + 8) + "px";
                $(".articleContent").css({
                    'font-size': articleFontSize
                });
                $(".fontSizeViewer").html(articleFontSize);
            }
        })
        let wordCount = $(".articleContent").html().length;
        $(".wordCounter").html("字数统计： " + wordCount);
        $(".articleContent").keyup(function() {
            let wordCount = $(this).html().length;
            $(".wordCounter").html("字数统计： " + wordCount);
        })

        $(".newArticleBtn").click(() => {
            repo = "写文章";
            var catagory = $(".newDiscussCatagory").val(),
                title = $(".articleTitle").val(),
                content = $(".articleContent").html() || $(".articleContent").val();
            $.post(
                "include/http/newArticle.php", {
                    title: title,
                    content: content,
                    cat: catagory
                },
                (data) => {
                    switch (data) {
                        case "E1":
                            $(".eventAlertModalMsg").html(
                                "没有登陆，不能" +
                                repo +
                                "。<br>不过怎么会出现这种错误？一定是出了什么故障。<br><small>当作没看见吧！</small>"
                            );
                            $("#eventAlertModal").modal("show");
                            $(".eventAlertCancelBtn").click(() => {
                                location.reload();
                            });
                            break;
                        case "E6":
                            $(".eventAlertModalMsg").html(repo + "这么快？快歇会儿。");
                            $("#eventAlertModal").modal("show");
                            break;
                        case "E7":
                            $(".eventAlertModalMsg").html(
                                "来到了不存在的分区？br><small>马上送你回去。</small>"
                            );
                            $("#eventAlertModal")
                                .attr({
                                    "data-backdrop": "static",
                                    "data-keyboard": "false",
                                })
                                .modal("show");
                            $(".eventAlertCancelBtn").click(() => {
                                window.location.href = "index.php";
                            });
                            break;
                        case "E4":
                            $(".eventAlertModalMsg").html("要不写点正文给大家看看？");
                            $("#eventAlertModal").modal("show");
                            break;
                        case "success":
                            $(window).scrollTop(0);
                            window.location.href = "view.php?id=" + data;
                            break;
                        case "E2":
                            $(".eventAlertModalMsg").html(
                                "怎么会出现这种错误？一定是出了什么故障。<br><small>当作没看见吧！</small>"
                            );
                            $("#eventAlertModal").modal("show");
                            $(".eventAlertCancelBtn").click(() => {
                                window.location.href = "index.php";
                            });
                            break;
                        case "E3":
                            $(".eventAlertModalMsg").html("余额不足以发起" + repo);
                            $("#eventAlertModal").modal("show");
                            $(".eventAlertCancelBtn").click(() => {
                                location.reload();
                            });
                            break;
                        default:
                            window.location.href = "view.php?id=" + data;

                    }
                }
            );
        });
        $(".continueArticleBtn").click(() => {
            repo = "写文章";
            var catagory = $(".newDiscussCatagory").val(),
                content = $(".articleContent").html() || $(".articleContent").val(),
                tid = <?php echo $article ?: "null"; ?>;
            $.post(
                "include/http/newreply.php", {
                    tid: tid,
                    content: content,
                },
                (data) => {
                    newREPOAlert(repo, data);
                    if (data == "success") {
                        window.location.href = "view.php?id=" + tid;
                    }
                }
            );
        });

    });
</script>