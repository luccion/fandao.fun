<?php
defined('ACC') || exit('ACC Denied');
$url = $_SERVER['SCRIPT_NAME'];
$query = explode('&', $_SERVER['QUERY_STRING']);
foreach ($query as $key => $val) {
	if (substr($val, 0, 4) === 'page' || $val === '') {
		unset($query[$key]);
	}
}

$query[] = '';
$query = implode('&', $query);
$url = $url . '?' . $query;

?>
<hr class="paginationHr" />
<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-center">
		<?php
		function print_page_ellipsis($curr_page)
		{
			echo '<li class="page-item disabled"><a class="page-link" alt="..."><i class="fa-solid fa-ellipsis"></i></a></li>';
			for ($i = 2; $i < $curr_page; $i++) {
				continue;
			}
		}
		if ($all <= 1) {
			echo '<li class="page-item disabled"><a class="page-link "><i class="fa-solid fa-face-flushed"></i>&nbsp;就这一页</a></li>';
		} else {
			if ($curr_page == 1) {
				echo '<li class="page-item disabled"><a class="page-link "><i class="fa-solid fa-people-pulling"></i></i></a></li>';
			} else if ($curr_page == 2) {
				echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($curr_page - 1) . '" alt="上一页"><i class="fa-solid fa-angle-left"></i></a></li>';
			} else {
				echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=1" alt="上一页"><i class="fa-solid fa-angles-left"></i></a></li>'; //上一页
				echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($curr_page - 1) . '" alt="第一页"><i class="fa-solid fa-angle-left"></i></a></li>'; //第一页
			}
			if ($all < 7) {
				for ($i = 1; $i <= $show; $i++) {
					if ($i == $curr_page) {
						echo '<li class="page-item active"><a class="page-link" alt="' . $i . '">' . fontawesomeNumber($i) . '</a></li>';
						continue;
					}
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . $i . '" alt="' . $i . '">' . fontawesomeNumber($i) . '</a></li>';
				}
			} else {
				echo '<li class="page-item ' . (($curr_page == 1) ? ("active") : "") . '"><a class="page-link" ' . (($curr_page != 1) ? ('href="' . $url . 'page=1"') : '') . ' alt="1">' . fontawesomeNumber(1) . '</a></li>';
				if ($curr_page == 1) {
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=2" alt="2">' . fontawesomeNumber(2) . '</a></li>';
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=3" alt="3">' . fontawesomeNumber(3) . '</a></li>';
					print_page_ellipsis($curr_page);
				} elseif ($curr_page == 2) {
					echo '<li class="page-item active"><a class="page-link" alt="' . $curr_page . '">' . fontawesomeNumber($curr_page) . '</a></li>';
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($curr_page + 1) . '" alt="' . ($curr_page + 1) . '">' . fontawesomeNumber(($curr_page + 1)) . '</a></li>';
					print_page_ellipsis($curr_page);
				} elseif ($curr_page == 3) {
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=2" alt="2">' . fontawesomeNumber(2) . '</a></li>';
					echo '<li class="page-item active"><a class="page-link" alt="' . $curr_page . '">' . fontawesomeNumber($curr_page) . '</a></li>';
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($curr_page + 1) . '" alt="' . ($curr_page + 1) . '">' . fontawesomeNumber($curr_page + 1) . '</a></li>';
					print_page_ellipsis($curr_page);
				} elseif ($curr_page > 3 && $curr_page < ($all - 3)) {
					echo '<li class="page-item disabled"><a class="page-link" alt="..."><i class="fa-solid fa-ellipsis"></i></a></li>';
					echo '<li class="page-item active"><a class="page-link" alt="' . $curr_page . '">' . fontawesomeNumber($curr_page) . '</a></li>';
					echo '<li class="page-item disabled"><a class="page-link" alt="..."><i class="fa-solid fa-ellipsis"></i></a></li>';
				} elseif ($curr_page == ($all - 3)) {
					print_page_ellipsis($curr_page);
					echo '<li class="page-item active"><a class="page-link" alt="' . $curr_page . '">' . fontawesomeNumber($curr_page) . '</a></li>';
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all - 2) . '" alt="' . ($all - 2) . '">' . fontawesomeNumber(($all - 2)) . '</a></li>';
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all - 1) . '" alt="' . ($all - 1) . '">' . fontawesomeNumber(($all - 1)) . '</a></li>';
				} elseif ($curr_page == ($all - 2)) {
					print_page_ellipsis($curr_page);
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all - 3) . '" alt="' . ($all - 3) . '">' . fontawesomeNumber(($all - 3)) . '</a></li>';
					echo '<li class="page-item active"><a class="page-link" alt="' . $curr_page . '">' . fontawesomeNumber($curr_page) . '</a></li>';
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all - 1) . '" alt="' . ($all - 1) . '">' . fontawesomeNumber(($all - 1)) . '</a></li>';
				} elseif ($curr_page == ($all - 1)) {
					print_page_ellipsis($curr_page);
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all - 2) . '" alt="' . ($all - 2) . '">' . fontawesomeNumber(($all - 2)) . '</a></li>';
					echo '<li class="page-item active"><a class="page-link" alt="' . $curr_page . '">' . fontawesomeNumber($curr_page) . '</a></li>';
				} elseif ($curr_page == $all) {
					print_page_ellipsis($curr_page);
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all - 2) . '" alt="' . ($all - 2) . '">' . fontawesomeNumber(($all - 2)) . '</a></li>';
					echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all - 1) . '" alt="' . ($all - 1) . '">' . fontawesomeNumber(($all - 1)) . '</a></li>';
				}
				echo '<li class="page-item ' . (($curr_page == $all) ? ("active") : "") . '"><a class="page-link" ' . (($curr_page != $all) ? ('href="' . $url . 'page=' . $all . '"') : '') . ' alt="' . $all . '">' . fontawesomeNumber($all) . '</a></li>';
			}

			if ($curr_page == ($all - 1)) {
				echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($curr_page + 1) . '" alt="下一页"><i class="fa-solid fa-angle-right"></i></a></li>'; //下一页
			} else if ($curr_page >= $all) {
				echo '<li class="page-item disabled"><a class="page-link "><i class="fa-solid fa-people-pulling fa-flip-horizontal"></i></a></li>';
			} else {
				echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($curr_page + 1) . '" alt="下一页"><i class="fa-solid fa-angle-right"></i></a></li>'; //下一页
				echo '<li class="page-item"><a class="page-link" href="' . $url . 'page=' . ($all) . '" alt="最后一页"><i class="fa-solid fa-angles-right"></i></a></li>'; //最后一页
			}
		}

		?>
	</ul>
</nav>