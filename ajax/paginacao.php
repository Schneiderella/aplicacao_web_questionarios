<?php

$output .= "
<nav aria-label='...' class='d-flex justify-content-center'>
  <ul class='pagination'>
";

$total_links = ceil($total_data / $limit);
$previous_link = '';
$next_link = '';
$page_link = '';
$page_array = [];

if ($total_links > 4) {
    if ($page < 5) {

        for ($count = 1; $count <= 5; $count++) {
            $page_array[] = $count;
        }

        $page_array[] = '...';
        $page_array[] = $total_links;
    } else {

        $end_limit = $total_links - 5;
        if ($page > $end_limit) {
            $page_array[] = 1;
            $page_array[] = '...';

            for ($count = $end_limit; $count <= $total_links; $count++) {
                $page_array[] = $count;
            }
        } else {

            $page_array[] = 1;
            $page_array[] = '...';
            for ($count = $page - 1; $count <= $page + 1; $count++) {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        }
    }
} else {
    for ($count = 1; $count <= $total_links; $count++) {
        $page_array[] = $count;
    }
}

for ($count = 0; $count < count($page_array); $count++) {
    if ($page == $page_array[$count]) {
        $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">' . $page_array[$count] . '</a>
    </li>
    ';

        $previous_id = $page_array[$count] - 1;
        if ($previous_id > 0) {
            $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $previous_id . '">Anterior</a></li>';
        } else {
            $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Anterior</a>
      </li>
      ';
        }
        $next_id = $page_array[$count] + 1;
        if ($next_id >= $total_links) {
            $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Próximo</a>
      </li>
        ';
        } else {
            $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $next_id . '">Próximo</a></li>';
        }
    } else {
        if ($page_array[$count] == '...') {
            $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
        } else {
            $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $page_array[$count] . '">' . $page_array[$count] . '</a></li>
      ';
        }
    }
}

$output .= $previous_link . $page_link . $next_link;
$output .= "
  </ul>

</nav>
";

// echo $output;
