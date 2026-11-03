<style>
    .section-header .actions {
        margin-left: auto;
    }
</style>
<div class="section-header">
    <h2 class="section-title">Breakdown</h2>
    <span class="section-sub">Total - <?= $vehicle_no ?> vehicles </span>
    <div class="actions">
        <span class="action-btn" id="btnCopyBreakdown"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="9" y="9" width="11" height="11" rx="1.5" />
                <path d="M5 15V5a2 2 0 0 1 2-2h10" />
            </svg>Copy</span>
        <span class="action-btn" id="btnPrintBreakdown"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 9V3h12v6" />
                <rect x="4" y="9" width="16" height="8" rx="1" />
                <path d="M6 17v4h12v-4" />
            </svg>Print</span>
        <div class="divider-v"></div>
        <span class="action-btn icon-only" id="btnExcelBreakdown" title="Export to Excel"><svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                <path d="M9 14l3 4M12 14l-3 4" />
            </svg></span>
        <span class="action-btn icon-only" id="btnCsvBreakdown" title="Export to CSV"><svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                <path d="M8 17v-3M11 17v-5M14 17v-2" />
            </svg></span>
        <span class="action-btn icon-only" id="btnPdfBreakdown" title="Export to PDF"><svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
            </svg></span>
    </div>
</div>
<div class="table-card">
    <table id="breakdownTable" class="display" style="width:100%">
        <thead>
            <tr class="col-row">
                <th class="left" style="width:40px;">No.</th>
                <th class="left">Description</th>
                <th>Expneses (<?= $selected_currency ?>)</th>
                <th>Income (<?= $selected_currency ?>)</th>
                <th style="width:60px; text-align:right;">% share</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $total_expneses_e = 0;
            $total_income_e = 0;
            $expneses_no = 0;
            $income_no=0;
            $total_share = 0;
            $total_vehicles = 0;
            $total = 0;
            $vehicle_count_array = [];
            foreach ($expenses_array as $row_expneses) {
                $expneses_no++;
                $vehicle_count = count($row_expneses['vehicle']);
                $cat_expenses = break_down($row_expneses['expenses'], $selected_currency, $currnecy_value);
                $avarage_per_expneses = $total_expenses / $vehicle_count;
                $share = ($cat_expenses / $total_expenses) * 100;
                $total_share += $share;
                $share = round($share, 1);
                $vehicle_count_array[] = $vehicle_count;
                $total_avg_per_vecihle = $total_expenses / $vehicle_no;
            
                    // echo json_encode($evaluation_table);
                    ?>
                <tr style="cursor: pointer;" class="expenses_tr" data-id="<?=$row_expneses['exp_id']?>"data-category="<?=$row_expneses['category']?>">
                    <td class="idx"><?= sprintf('%03d', $expneses_no); ?></td>
                    <td><?= $row_expneses['category'] ?></td>
                    <td class="num" style="font-weight:700; color:var(--ink-900);">
                        <?php
                        //echo json_encode($row_income['income']);
                    
                        $total_expneses_e += $cat_expenses;
                        echo number_format($cat_expenses);
                        ?>
                    </td>
                    <td class="num">-</td>
                    <td class="num" style="padding-left:16px;">
                        <span class="pct-label"> <?= $share; ?>%</span>
                    </td>

                </tr>

                <?php
                
            }
            
         $avarage_vehicle_count = (empty($vehicle_count_array))  ? 0 : max($vehicle_count_array);
            ?>
            <tr class="footer">
                <td class="idx"></td>
                <td></td>
                <td class="num" style="font-weight:700; color:var(--ink-900);">
                    <?= number_format($total_expneses_e) ?>
                </td>
                <td class="num">-</td>
                <td style="padding-left:16px;">
                    <?= $total_share ?>%
                </td>

            </tr>
            <?php
            $vehicle_count_array = [];
            $total_share = 0;
            foreach ($income_array as $row_income) {
                $income_no++;
                $id_replace = str_replace(' ', '_', $row_income['category']);
                $id = $id_replace."_Income";
                
                $vehicle_count = count($row_income['vehicle']);
                $cat_income = break_down($row_income['income'], $selected_currency, $currnecy_value);
                $avarage_per_income = $grand_total / $vehicle_count;
                $share = ($cat_income / $grand_total) * 100;
                $total_share += $share;
                $share = round($share, 1);


                ?>
                <tr style="cursor: pointer;" class="income_tr_s" data-id="<?=$id?>"data-category="<?=$row_income['category']?>">
                    <td class="idx"><?= sprintf('%03d', $income_no); ?></td>
                    <td><?= $row_income['category'] ?></td>
                    <td class="num"> - </td>
                    <td class="num" style="font-weight:700; color:var(--ink-900);">
                        <?php
                        //echo json_encode($row_income['income']);
                        $total_income_e += $cat_income;
                        echo number_format($cat_income);
                        ?>
                    </td>
                    <td style="padding-left:16px;"><span class="pct-label"><?= $share ?>%</span></td>

                </tr>

                <?php
            }
            ?>
            <tr class="footer">
                <td class="idx"></td>
                <td></td>
                <td class="num">

                </td>
                <td class="num" style="font-weight:700; color:var(--ink-900);">
                    <?= number_format($total_income_e) ?>
                </td>
                <td style="padding-left:16px;">
                    <div class="bar-cell">
                        <?= round($total_share) ?>%
                    </div>
                </td>

            </tr>
            <tr >
                <td></td>
                <td>Total Balance</td>
                <td class="num">

                </td>
                <td class="num" style="font-weight:700; color:var(--ink-900);">
                    <?= number_format($total_income_e - $total_expneses_e) ?>
                </td>
                <td style="padding-left:16px;">
                    <div class="bar-cell">

                    </div>
                </td>

            </tr>
        </tbody>

    </table>

</div>
<script>
    if ($.fn.DataTable.isDataTable('#breakdownTable')) {
        $('#breakdownTable').DataTable().destroy();
    }
    window.breakdownTable = $('#breakdownTable').DataTable({
        paging: false,
        info: false,
        searching: false,
        ordering: false,
        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: null,
            bottomEnd: null
        },
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Vehicle Breakdown <?= $start_date_adopted ?> to <?= $end_date_adopted ?>'
            },
            {
                extend: 'csvHtml5',
                title: 'Vehicle Breakdown <?= $start_date_adopted ?> to <?= $end_date_adopted ?>'
            },
            {
                extend: 'excelHtml5',
                title: 'Vehicle Breakdown <?= $start_date_adopted ?> to <?= $end_date_adopted ?>'
            },
            {
                extend: 'pdfHtml5',
                title: 'Vehicle Breakdown <?= $start_date_adopted ?> to <?= $end_date_adopted ?>',
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                title: 'Vehicle Breakdown <?= $start_date_adopted ?> to <?= $end_date_adopted ?>'
            }
        ]
    });

    $('#btnCopyBreakdown').off('click').on('click', function () {
        window.breakdownTable.button('.buttons-copy').trigger();
    });
    $('#btnCsvBreakdown').off('click').on('click', function () {
        window.breakdownTable.button('.buttons-csv').trigger();
    });
    $('#btnExcelBreakdown').off('click').on('click', function () {
        window.breakdownTable.button('.buttons-excel').trigger();
    });
    $('#btnPdfBreakdown').off('click').on('click', function () {
        window.breakdownTable.button('.buttons-pdf').trigger();
    });
    $('#btnPrintBreakdown').off('click').on('click', function () {
        window.breakdownTable.button('.buttons-print').trigger();
    });
</script>