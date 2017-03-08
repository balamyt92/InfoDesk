<?php
/* @var $data array */


$days = $data['categories'];
$date_column = $data['series'];
$total_sum = 0;
$sum_by_column = [];
$rows = [];

foreach ($days as $key => $day) {
    $tmp = [$day];
    $sum = 0;
    foreach ($date_column as $i => $column) {
        $tmp[] = $column['data'][$key];
        $sum += $column['data'][$key];
        $sum_by_column[$column['name']] = isset($sum_by_column[$column['name']])
            ? $sum_by_column[$column['name']] + $column['data'][$key]
            : $column['data'][$key];
    }
    $tmp[] = $sum;
    $total_sum += $sum;
    $rows[] = $tmp;
}

?>

<div class="table-responsive">
    <table class="table">
        <thead>
        <tr class="active">
            <td>
                День
            </td>
            <? foreach ($date_column as $item): ?>
                <td>
                    <?= $item['name'] ?>
                </td>
            <? endforeach; ?>
            <td>
                Сумма дня
            </td>
        </tr>
        </thead>
        <tbody>
        <? foreach ($rows as $row): ?>
        <tr>
            <? foreach ($row as $cell): ?>
            <td>
                <?= $cell ?>
            </td>
            <? endforeach; ?>
        </tr>
        <? endforeach; ?>
        <tr class="success">
            <td>ИТОГО:</td>
            <? foreach ($sum_by_column as $item): ?>
                <td>
                    <?= $item ?>
                </td>
            <? endforeach; ?>
            <td>
                <?= $total_sum ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
