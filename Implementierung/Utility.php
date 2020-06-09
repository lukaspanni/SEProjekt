<?php

function return_404_error()
{
    http_response_code(404);
    $view = new ErrorView(new ApplicationError(ErrorType::ERROR_404, "This site doesn't exist."));
    $view->render();
}

function return_501_error()
{
    http_response_code(501);
    $view = new ErrorView(new ApplicationError(ErrorType::ERROR_501, "Not Implemented"));
    $view->render();
}

/**
 * Utility for creating a pagination
 * @param $page string: page where the pagination is embedded
 * @param $count int: total number of elements
 * @param $interval int: number of elements per pagination-page
 * @param $current int: current pagination-page
 */
function listPagination($page, $count, $interval, $current)
{
    $pagination_items = ceil($count / $interval);
    if ($pagination_items > 1) {
        ?>
        <ul class="pagination center">
            <?php
            $prev = $current - 1;
            $next = $current + 1;
            $prev_class = "waves-effect";
            $next_class = "waves-effect";
            if ($prev < 1) $prev_class = "disabled";
            if ($next > $pagination_items) $next_class = "disabled";
            echo '<li class="' . $prev_class . '"><a href="/' . $page . '/all/' . $prev . '"><i class="material-icons">chevron_left</i></a></li>';

            for ($i = 1; $i <= $pagination_items; $i++) {
                if ($current == $i) {
                    $class = "active";
                } else {
                    $class = "waves-effect";
                }
                echo "<li class='$class'><a href='/$page/all/$i'>$i</a></li>";

            }
            echo '<li class="' . $next_class . '"><a href="/' . $page . '/all/' . $next . '"><i class="material-icons">chevron_right</i></a></li>';
            ?>
        </ul>

        <?php
    }
}

/**
 * Utility function to convert a timespan in minutes into a time string (hh:mm)
 * @param $minutes int: timespan in minutes
 * @return string
 */
function minutesToTimeString($minutes)
{
    $hours = $minutes / 60;
    $minutes = ($hours - intval($hours)) * 60;
    return intval($hours) . "h " . intval($minutes) . "min";
}

?>
