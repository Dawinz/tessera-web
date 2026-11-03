  <div class="section-header">
    <h2 class="section-title">Expenses breakdown</h2>
    <span class="section-sub">440,981,902 Tsh total - 16 vehicles - Jun 2026</span>
  </div>
   <div class="table-card">
    <table>
      <thead>
        <tr class="col-row">
          <th class="left" style="width:40px;">No.</th>
          <th class="left">Category</th>
          <th>Amount (Tsh)</th>
          <th style="width:60px; text-align:right;">% share</th>
          <th class="left" style="padding-left:16px; width:260px;">Distribution</th>
          <th>Vehicles</th>
          <th>Avg per vehicle</th>
        </tr>
      </thead>
      <tbody>
          <?php
         $expneses_no = 0;
        foreach ($expenses_array as $row_expneses) {
            $expneses_no++;
        ?>
        <tr>
          <td class="idx"><?= sprintf('%03d', $expneses_no); ?></td>
          <td><span class="cat-badge" style="background:#FEF6E7; color:#965E06;"><span class="cat-dot" style="background:#E09C18;"></span><?= $row_expneses['name']?></span></td>
          <td class="num" style="font-weight:700; color:var(--ink-900);">
               <?php
          //echo json_encode($row_income['income']);
          $cat_expenses =  break_down($row_expneses['expenses'], $selected_currency, $currnecy_value);
          echo number_format($cat_expenses);
          ?>
          </td>
          <td class="num"><span class="pct-label">40.8%</span></td>
          <td style="padding-left:16px;"><div class="bar-cell"><div class="bar-wrap"><div class="bar-fill" style="width:40.8%; background:#E09C18;"></div></div></div></td>
          <td class="num">16</td>
          <td class="num">11,250,000</td>
        </tr>
     
     <?php
        }
     ?>
     
      </tbody>
      <tfoot>
        <tr>
          <td class="label" colspan="2">Total expenses - 6 categories</td>
          <td class="num">440,981,902</td>
          <td class="num"><span class="pct-label" style="color:var(--purple-700);">100%</span></td>
          <td style="padding-left:16px;">
            <div class="bar-cell">
              <div class="bar-wrap" style="height:10px;">
                <div style="display:flex; height:100%; border-radius:4px; overflow:hidden;">
                  <div style="width:40.8%; background:#E09C18;"></div>
                  <div style="width:27.2%; background:#6357E8;"></div>
                  <div style="width:14.1%; background:#2B7FD6;"></div>
                  <div style="width:10.2%; background:#16A35E;"></div>
                  <div style="width:6.3%; background:#D35030;"></div>
                  <div style="flex:1; background:#888780;"></div>
                </div>
              </div>
            </div>
          </td>
          <td class="num">16</td>
          <td class="num">27,561,369</td>
        </tr>
      </tfoot>
    </table>
    </div>