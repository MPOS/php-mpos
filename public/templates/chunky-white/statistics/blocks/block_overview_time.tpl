<article class="widget col-md-11">
  <header><h3>Block Overview</h3></header>
  <table width="100%" class="table table-striped" cellspacing="0">
    <thead>
        <tr>
          <th align="left"></th>
          <th align="center">Gen Est.</th>
          <th align="center">Found</th>
          <th align="center">Valid</th>
          <th align="center">Orphan</th>
          <th align="center">Avg Diff</th>
          <th align="center">Shares Est.</th>
          <th align="center">Shares</th>
          <th align="center">Percentage</th>
          <th align="center">Amount</th>
          <th align="center">Rate Est.</th>
        </tr>
    </thead>
    <tbody>
        <tr>
          <th align="left" style="padding-left: 15px">All Time</td>
          <td align="center">{($FIRSTBLOCKFOUND / $COINGENTIME)|number_format:"0"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.Total}</td>
          <td align="center">{$LASTBLOCKSBYTIME.TotalValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.TotalOrphan}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.TotalValid > 0}
              {($LASTBLOCKSBYTIME.TotalDifficulty / $LASTBLOCKSBYTIME.TotalValid)|number_format:"4"}
            {else}
              0
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.TotalEstimatedShares}</td>
          <td align="center">{$LASTBLOCKSBYTIME.TotalShares}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.TotalEstimatedShares > 0}
              <span class="{if (($LASTBLOCKSBYTIME.TotalShares / $LASTBLOCKSBYTIME.TotalEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.TotalShares / $LASTBLOCKSBYTIME.TotalEstimatedShares * 100)|number_format:"2"}%</span></b>
            {else}
              0.00%
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.TotalAmount}</td>
          <td align="center">{($LASTBLOCKSBYTIME.Total|default:"0.00" / ($FIRSTBLOCKFOUND / $COINGENTIME)  * 100)|number_format:"2"}%</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last Hour</td>
          <td align="center">{(3600 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourOrphan}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.1HourValid > 0}
              {($LASTBLOCKSBYTIME.1HourDifficulty / $LASTBLOCKSBYTIME.1HourValid)|number_format:"4"}
            {else}
              0
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourEstimatedShares}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourShares}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.1HourEstimatedShares > 0}
              <span class="{if (($LASTBLOCKSBYTIME.1HourShares / $LASTBLOCKSBYTIME.1HourEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.1HourShares / $LASTBLOCKSBYTIME.1HourEstimatedShares * 100)|number_format:"2"}%</span></b>
            {else}
              0.00%
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourAmount}</td>
          <td align="center">{($LASTBLOCKSBYTIME.1HourTotal|default:"0.00" / (3600 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 24 Hours</td>
          <td align="center">{(86400 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourOrphan}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.24HourValid > 0}
              {($LASTBLOCKSBYTIME.24HourDifficulty / $LASTBLOCKSBYTIME.24HourValid)|number_format:"4"}
            {else}
              0
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourEstimatedShares}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourShares}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.24HourEstimatedShares > 0}
              <span class="{if (($LASTBLOCKSBYTIME.24HourShares / $LASTBLOCKSBYTIME.24HourEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.24HourShares / $LASTBLOCKSBYTIME.24HourEstimatedShares * 100)|number_format:"2"}%</span></b>
            {else}
              0.00%
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourAmount}</td>
          <td align="center">{($LASTBLOCKSBYTIME.24HourTotal|default:"0.00" / (86400 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 7 Days</td>
          <td align="center">{(604800 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysOrphan}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.7DaysValid > 0}
              {($LASTBLOCKSBYTIME.7DaysDifficulty / $LASTBLOCKSBYTIME.7DaysValid)|number_format:"4"}
            {else}
              0
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysEstimatedShares}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysShares}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.7DaysEstimatedShares > 0}
              <span class="{if (($LASTBLOCKSBYTIME.7DaysShares / $LASTBLOCKSBYTIME.7DaysEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.7DaysShares / $LASTBLOCKSBYTIME.7DaysEstimatedShares * 100)|number_format:"2"}%</span></b>
            {else}
              0.00%
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysAmount}</td>
          <td align="center">{($LASTBLOCKSBYTIME.7DaysTotal|default:"0.00" / (604800 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 4 Weeks</td>
          <td align="center">{(2419200 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksOrphan}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.4WeeksValid > 0}
              {($LASTBLOCKSBYTIME.4WeeksDifficulty / $LASTBLOCKSBYTIME.4WeeksValid)|number_format:"4"}
            {else}
              0
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksEstimatedShares}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksShares}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.4WeeksEstimatedShares > 0}
              <span class="{if (($LASTBLOCKSBYTIME.4WeeksShares / $LASTBLOCKSBYTIME.4WeeksEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.4WeeksShares / $LASTBLOCKSBYTIME.4WeeksEstimatedShares * 100)|number_format:"2"}%</span></b>
            {else}
              0.00%
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksAmount}</td>
          <td align="center">{($LASTBLOCKSBYTIME.4WeeksTotal|default:"0.00" / (2419200 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 12 Month</td>
          <td align="center">{(29030400 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthOrphan}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.12MonthValid > 0}
              {($LASTBLOCKSBYTIME.12MonthDifficulty / $LASTBLOCKSBYTIME.12MonthValid)|number_format:"4"}
            {else}
              0
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthEstimatedShares}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthShares}</td>
          <td align="center">
            {if $LASTBLOCKSBYTIME.12MonthEstimatedShares > 0}
              <span class="{if (($LASTBLOCKSBYTIME.12MonthShares / $LASTBLOCKSBYTIME.12MonthEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.12MonthShares / $LASTBLOCKSBYTIME.12MonthEstimatedShares * 100)|number_format:"2"}%</span></b>
            {else}
              0.00%
            {/if}
          </td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthAmount}</td>
          <td align="center">{($LASTBLOCKSBYTIME.12MonthTotal|default:"0.00" / (29030400 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
        </tr>
    </tbody>
  </table>
</article>
