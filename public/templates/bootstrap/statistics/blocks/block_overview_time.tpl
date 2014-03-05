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
                <th>Gen est.</th>
                <th>Found</th>
                <th>Valid</th>
                <th>Orphan</th>
                <th>Avg Diff</th>
                <th>Shares est.</th>
                <th>Shares</th>
                <th>Percentage</th>
                <th>Amount</th>
                <th>Rate est.</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th style="padding-left: 15px">all time</th>
                <td>{($FIRSTBLOCKFOUND / $COINGENTIME)|number_format:"0"}</td>
                <td>{$LASTBLOCKSBYTIME.Total}</td>
                <td>{$LASTBLOCKSBYTIME.TotalValid}</td>
                <td>{$LASTBLOCKSBYTIME.TotalOrphan}</td>
                <td>
                {if $LASTBLOCKSBYTIME.TotalValid > 0}
                  {($LASTBLOCKSBYTIME.TotalDifficulty / $LASTBLOCKSBYTIME.TotalValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.TotalEstimatedShares}</td>
                <td>{$LASTBLOCKSBYTIME.TotalShares}</td>
                <td>
                {if $LASTBLOCKSBYTIME.TotalEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.TotalShares / $LASTBLOCKSBYTIME.TotalEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.TotalShares / $LASTBLOCKSBYTIME.TotalEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.TotalAmount}</td>
                <td>{($LASTBLOCKSBYTIME.Total|default:"0.00" / ($FIRSTBLOCKFOUND / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last hour</th>
                <td>{(3600 / $COINGENTIME)}</td>
                <td>{$LASTBLOCKSBYTIME.1HourTotal}</td>
                <td>{$LASTBLOCKSBYTIME.1HourValid}</td>
                <td>{$LASTBLOCKSBYTIME.1HourOrphan}</td>
                <td>
                {if $LASTBLOCKSBYTIME.1HourValid > 0}
                  {($LASTBLOCKSBYTIME.1HourDifficulty / $LASTBLOCKSBYTIME.1HourValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.1HourEstimatedShares}</td>
                <td>{$LASTBLOCKSBYTIME.1HourShares}</td>
                <td>
                {if $LASTBLOCKSBYTIME.1HourEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.1HourShares / $LASTBLOCKSBYTIME.1HourEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.1HourShares / $LASTBLOCKSBYTIME.1HourEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.1HourAmount}</td>
                <td>{($LASTBLOCKSBYTIME.1HourTotal|default:"0.00" / (3600 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 24 hours</th>
                <td>{(86400 / $COINGENTIME)}</td>
                <td>{$LASTBLOCKSBYTIME.24HourTotal}</td>
                <td>{$LASTBLOCKSBYTIME.24HourValid}</td>
                <td>{$LASTBLOCKSBYTIME.24HourOrphan}</td>
                <td>
                {if $LASTBLOCKSBYTIME.24HourValid > 0}
                  {($LASTBLOCKSBYTIME.24HourDifficulty / $LASTBLOCKSBYTIME.24HourValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.24HourEstimatedShares}</td>
                <td>{$LASTBLOCKSBYTIME.24HourShares}</td>
                <td>
                {if $LASTBLOCKSBYTIME.24HourEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.24HourShares / $LASTBLOCKSBYTIME.24HourEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.24HourShares / $LASTBLOCKSBYTIME.24HourEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.24HourAmount}</td>
                <td>{($LASTBLOCKSBYTIME.24HourTotal|default:"0.00" / (86400 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 7 days</th>
                <td>{(604800 / $COINGENTIME)}</td>
                <td>{$LASTBLOCKSBYTIME.7DaysTotal}</td>
                <td>{$LASTBLOCKSBYTIME.7DaysValid}</td>
                <td>{$LASTBLOCKSBYTIME.7DaysOrphan}</td>
                <td>
                {if $LASTBLOCKSBYTIME.7DaysValid > 0}
                  {($LASTBLOCKSBYTIME.7DaysDifficulty / $LASTBLOCKSBYTIME.7DaysValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.7DaysEstimatedShares}</td>
                <td>{$LASTBLOCKSBYTIME.7DaysShares}</td>
                <td>
                {if $LASTBLOCKSBYTIME.7DaysEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.7DaysShares / $LASTBLOCKSBYTIME.7DaysEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.7DaysShares / $LASTBLOCKSBYTIME.7DaysEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.7DaysAmount}</td>
                <td>{($LASTBLOCKSBYTIME.7DaysTotal|default:"0.00" / (604800 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 4 Weeks</th>
                <td>{(2419200 / $COINGENTIME)}</td>
                <td>{$LASTBLOCKSBYTIME.4WeeksTotal}</td>
                <td>{$LASTBLOCKSBYTIME.4WeeksValid}</td>
                <td>{$LASTBLOCKSBYTIME.4WeeksOrphan}</td>
                <td>
                {if $LASTBLOCKSBYTIME.4WeeksValid > 0}
                  {($LASTBLOCKSBYTIME.4WeeksDifficulty / $LASTBLOCKSBYTIME.4WeeksValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.4WeeksEstimatedShares}</td>
                <td>{$LASTBLOCKSBYTIME.4WeeksShares}</td>
                <td>
                {if $LASTBLOCKSBYTIME.4WeeksEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.4WeeksShares / $LASTBLOCKSBYTIME.4WeeksEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.4WeeksShares / $LASTBLOCKSBYTIME.4WeeksEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.4WeeksAmount}</td>
                <td>{($LASTBLOCKSBYTIME.4WeeksTotal|default:"0.00" / (2419200 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 12 Month</th>
                <td>{(29030400 / $COINGENTIME)}</td>
                <td>{$LASTBLOCKSBYTIME.12MonthTotal}</td>
                <td>{$LASTBLOCKSBYTIME.12MonthValid}</td>
                <td>{$LASTBLOCKSBYTIME.12MonthOrphan}</td>
                <td>
                {if $LASTBLOCKSBYTIME.12MonthValid > 0}
                  {($LASTBLOCKSBYTIME.12MonthDifficulty / $LASTBLOCKSBYTIME.12MonthValid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.12MonthEstimatedShares}</td>
                <td>{$LASTBLOCKSBYTIME.12MonthShares}</td>
                <td>
                {if $LASTBLOCKSBYTIME.12MonthEstimatedShares > 0}
                  <font color="{if (($LASTBLOCKSBYTIME.12MonthShares / $LASTBLOCKSBYTIME.12MonthEstimatedShares * 100) <= 100)}green{else}red{/if}">{($LASTBLOCKSBYTIME.12MonthShares / $LASTBLOCKSBYTIME.12MonthEstimatedShares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$LASTBLOCKSBYTIME.12MonthAmount}</td>
                <td>{($LASTBLOCKSBYTIME.12MonthTotal|default:"0.00" / (29030400 / $COINGENTIME)  * 100)|number_format:"2"}%</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="panel-footer">
        {if $GLOBAL.config.payout_system != 'pps'}Round earnings are not credited until <font color="orange">{$GLOBAL.confirmations}</font> confirms.{/if}
      </div>
    </div>
  </div>
</div>
