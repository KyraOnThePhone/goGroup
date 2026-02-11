<?php
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : date('Y');

if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

$firstDayOfMonth = date('N', strtotime("$year-$month-01"));
$daysInMonth = date('t', strtotime("$year-$month-01"));   

$monthName = date('F Y', strtotime("$year-$month-01"));

$termine = [9, 17, 23];
?>

<div class="side-box kalender-box z-depth-1">

    <div class="kalender-nav">
        <a href="?month=<?= $month - 1 ?>&year=<?= $year ?>">
            <i class="material-icons">chevron_left</i>
        </a>

        <h5><?= $monthName ?></h5>

        <a href="?month=<?= $month + 1 ?>&year=<?= $year ?>">
            <i class="material-icons">chevron_right</i>
        </a>
    </div>

    <table class="kalender">
        <thead>
            <tr>
                <th>Mo</th><th>Di</th><th>Mi</th>
                <th>Do</th><th>Fr</th><th>Sa</th><th>So</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo "<tr>";

            for ($i = 1; $i < $firstDayOfMonth; $i++) {
                echo "<td></td>";
            }

            for ($day = 1; $day <= $daysInMonth; $day++) {

                $classes = [];

                if (in_array($day, $termine)) {
                    $classes[] = 'termin';
                }

                if (
                    $day == date('j') &&
                    $month == date('n') &&
                    $year == date('Y')
                ) {
                    $classes[] = 'heute';
                }

                $classAttr = $classes ? ' class="'.implode(' ', $classes).'"' : '';
                echo "<td$classAttr>$day</td>";

                if ((($day + $firstDayOfMonth - 1) % 7) === 0 && $day !== $daysInMonth) {
                    echo "</tr><tr>";
                }
            }

            echo "</tr>";
            ?>
        </tbody>
    </table>

    <div class="kalender-legende">
        <span class="punkt termin"></span> Termin
        <span class="punkt heute"></span> Heute
    </div>

</div>
