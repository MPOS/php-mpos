<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-clock-o fa-fw"></i> Block Overview
      </div>
      <div class="panel-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th></th>
                <th class="text-right">Gen. Est.</th>
                <th class="text-right">Found</th>
                <th class="text-right">Valid</th>
                <th class="text-right">Orphan</th>
                <th class="text-right">Orphan %</th>
                <th class="text-right">Avg. Diff</th>
                <th class="text-right">Shares Est.</th>
                <th class="text-right">Shares</th>
                <th class="text-right">Percentage</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Rate Est.</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>All Time</th>
                <td class="text-right">{($FIRSTBLOCKFOUND / $COINGENTIME)|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.Total|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.TotalValid|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.TotalOrphan|number_format}</td>
                <td class="text-right">{if $LASTBLOCKSBYTIME.TotalOrphan|default:"0" > 0}{(100 / $LASTBLOCKSBYTIME.Total * $LASTBLOCKSBYTIME.TotalOrphan)|number_format:"2"}{else}0.00{/if}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.TotalValid > 0}
                  {($LASTBLOCKSBYTIME.TotalDifficulty / $LASTBLOCKSBYTIME.TotalValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.TotalEstimatedShares|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.TotalShares|number_format}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.TotalEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.TotalShares / $LASTBLOCKSBYTIME.TotalEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.TotalShares / $LASTBLOCKSBYTIME.TotalEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.TotalAmount|number_format:"2"}</td>
                <td class="text-right">{($LASTBLOCKSBYTIME.Total|default:"0.00" / ($FIRSTBLOCKFOUND / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th>Last Hour</th>
                <td class="text-right">{(3600 / $COINGENTIME)|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.1HourTotal|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.1HourValid|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.1HourOrphan|number_format}</td>
                <td class="text-right">{if $LASTBLOCKSBYTIME.1HourOrphan|default:"0" > 0}{(100 / $LASTBLOCKSBYTIME.1HourTotal * $LASTBLOCKSBYTIME.1HourOrphan)|number_format:"2"}{else}0.00{/if}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.1HourValid > 0}
                  {($LASTBLOCKSBYTIME.1HourDifficulty / $LASTBLOCKSBYTIME.1HourValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.1HourEstimatedShares|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.1HourShares|number_format}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.1HourEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.1HourShares / $LASTBLOCKSBYTIME.1HourEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.1HourShares / $LASTBLOCKSBYTIME.1HourEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.1HourAmount|number_format:"2"}</td>
                <td class="text-right">{($LASTBLOCKSBYTIME.1HourTotal|default:"0.00" / (3600 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left:3px;padding-right:1px;">Last 24 Hours</th>
                <td class="text-right">{(86400 / $COINGENTIME)|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.24HourTotal|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.24HourValid|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.24HourOrphan|number_format}</td>
                <td class="text-right">{if $LASTBLOCKSBYTIME.24HourOrphan|default:"0" > 0}{(100 / $LASTBLOCKSBYTIME.24HourTotal * $LASTBLOCKSBYTIME.24HourOrphan)|number_format:"2"}{else}0.00{/if}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.24HourValid > 0}
                  {($LASTBLOCKSBYTIME.24HourDifficulty / $LASTBLOCKSBYTIME.24HourValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.24HourEstimatedShares|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.24HourShares|number_format}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.24HourEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.24HourShares / $LASTBLOCKSBYTIME.24HourEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.24HourShares / $LASTBLOCKSBYTIME.24HourEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.24HourAmount|number_format:"2"}</td>
                <td class="text-right">{($LASTBLOCKSBYTIME.24HourTotal|default:"0.00" / (86400 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th>Last 7 Days</th>
                <td class="text-right">{(604800 / $COINGENTIME)|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.7DaysTotal|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.7DaysValid|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.7DaysOrphan|number_format}</td>
                <td class="text-right">{if $LASTBLOCKSBYTIME.7DaysOrphan|default:"0" > 0}{(100 / $LASTBLOCKSBYTIME.7DaysTotal * $LASTBLOCKSBYTIME.7DaysOrphan)|number_format:"2"}{else}0.00{/if}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.7DaysValid > 0}
                  {($LASTBLOCKSBYTIME.7DaysDifficulty / $LASTBLOCKSBYTIME.7DaysValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.7DaysEstimatedShares|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.7DaysShares|number_format}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.7DaysEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.7DaysShares / $LASTBLOCKSBYTIME.7DaysEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.7DaysShares / $LASTBLOCKSBYTIME.7DaysEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.7DaysAmount|number_format:"2"}</td>
                <td class="text-right">{($LASTBLOCKSBYTIME.7DaysTotal|default:"0.00" / (604800 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th>Last 4 Weeks</th>
                <td class="text-right">{(2419200 / $COINGENTIME)|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.4WeeksTotal|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.4WeeksValid|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.4WeeksOrphan|number_format}</td>
                <td class="text-right">{if $LASTBLOCKSBYTIME.4WeeksOrphan|default:"0" > 0}{(100 / $LASTBLOCKSBYTIME.4WeeksTotal * $LASTBLOCKSBYTIME.4WeeksOrphan)|number_format:"2"}{else}0.00{/if}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.4WeeksValid > 0}
                  {($LASTBLOCKSBYTIME.4WeeksDifficulty / $LASTBLOCKSBYTIME.4WeeksValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.4WeeksEstimatedShares|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.4WeeksShares|number_format}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.4WeeksEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.4WeeksShares / $LASTBLOCKSBYTIME.4WeeksEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.4WeeksShares / $LASTBLOCKSBYTIME.4WeeksEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.4WeeksAmount|number_format:"2"}</td>
                <td class="text-right">{($LASTBLOCKSBYTIME.4WeeksTotal|default:"0.00" / (2419200 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th>The Past 12 Months</th>
                <td class="text-right">{(29030400 / $COINGENTIME)|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.12MonthTotal|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.12MonthValid|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.12MonthOrphan|number_format}</td>
                <td class="text-right">{if $LASTBLOCKSBYTIME.12MonthOrphan|default:"0" > 0}{(100 / $LASTBLOCKSBYTIME.12MonthTotal * $LASTBLOCKSBYTIME.12MonthOrphan)|number_format:"2"}{else}0.00{/if}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.12MonthValid > 0}
                  {($LASTBLOCKSBYTIME.12MonthDifficulty / $LASTBLOCKSBYTIME.12MonthValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.12MonthEstimatedShares|number_format}</td>
                <td class="text-right">{$LASTBLOCKSBYTIME.12MonthShares|number_format}</td>
                <td class="text-right">
                {if $LASTBLOCKSBYTIME.12MonthEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.12MonthShares / $LASTBLOCKSBYTIME.12MonthEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.12MonthShares / $LASTBLOCKSBYTIME.12MonthEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td class="text-right">{$LASTBLOCKSBYTIME.12MonthAmount|number_format:"2"}</td>
                <td class="text-right">{($LASTBLOCKSBYTIME.12MonthTotal|default:"0.00" / (29030400 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="panel-footer">
        <h6>{if $GLOBAL.config.payout_system != 'pps'}Round earnings are not credited until <font class="confirmations">{$GLOBAL.confirmations}</font> confirms.{/if}</h6>
      </div>
    </div>
  </div>
</div>
