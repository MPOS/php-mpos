<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-clock-o fa-fw"></i> Block Overview
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th></th>
                <th>gen est.</th>
                <th>found</th>
                <th>valid</th>
                <th>orphan</th>
                <th>avg diff</th>
                <th>shares est.</th>
                <th>shares</th>
                <th>percentage</th>
                <th>amount</th>
                <th>rate est.</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th style="padding-left: 15px">all time</th>
                <td>{($firstblockfound / $coingentime)|number_format:"0"}</td>
                <td>{$lastblocksbytime.total}</td>
                <td>{$lastblocksbytime.totalvalid}</td>
                <td>{$lastblocksbytime.totalorphan}</td>
                <td>
                {if $lastblocksbytime.totalvalid > 0}
                  {($lastblocksbytime.totaldifficulty / $lastblocksbytime.totalvalid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$lastblocksbytime.totalestimatedshares}</td>
                <td>{$lastblocksbytime.totalshares}</td>
                <td>
                {if $lastblocksbytime.totalestimatedshares > 0}
                  <font color="{if (($lastblocksbytime.totalshares / $lastblocksbytime.totalestimatedshares * 100) <= 100)}green{else}red{/if}">{($lastblocksbytime.totalshares / $lastblocksbytime.totalestimatedshares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$lastblocksbytime.totalamount}</td>
                <td>{($lastblocksbytime.total|default:"0.00" / ($firstblockfound / $coingentime)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last hour</th>
                <td>{(3600 / $coingentime)}</td>
                <td>{$lastblocksbytime.1hourtotal}</td>
                <td>{$lastblocksbytime.1hourvalid}</td>
                <td>{$lastblocksbytime.1hourorphan}</td>
                <td>
                {if $lastblocksbytime.1hourvalid > 0}
                  {($lastblocksbytime.1hourdifficulty / $lastblocksbytime.1hourvalid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$lastblocksbytime.1hourestimatedshares}</td>
                <td>{$lastblocksbytime.1hourshares}</td>
                <td>
                {if $lastblocksbytime.1hourestimatedshares > 0}
                  <font color="{if (($lastblocksbytime.1hourshares / $lastblocksbytime.1hourestimatedshares * 100) <= 100)}green{else}red{/if}">{($lastblocksbytime.1hourshares / $lastblocksbytime.1hourestimatedshares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$lastblocksbytime.1houramount}</td>
                <td>{($lastblocksbytime.1hourtotal|default:"0.00" / (3600 / $coingentime)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 24 hours</th>
                <td>{(86400 / $coingentime)}</td>
                <td>{$lastblocksbytime.24hourtotal}</td>
                <td>{$lastblocksbytime.24hourvalid}</td>
                <td>{$lastblocksbytime.24hourorphan}</td>
                <td>
                {if $lastblocksbytime.24hourvalid > 0}
                  {($lastblocksbytime.24hourdifficulty / $lastblocksbytime.24hourvalid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$lastblocksbytime.24hourestimatedshares}</td>
                <td>{$lastblocksbytime.24hourshares}</td>
                <td>
                {if $lastblocksbytime.24hourestimatedshares > 0}
                  <font color="{if (($lastblocksbytime.24hourshares / $lastblocksbytime.24hourestimatedshares * 100) <= 100)}green{else}red{/if}">{($lastblocksbytime.24hourshares / $lastblocksbytime.24hourestimatedshares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$lastblocksbytime.24houramount}</td>
                <td>{($lastblocksbytime.24hourtotal|default:"0.00" / (86400 / $coingentime)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 7 days</th>
                <td>{(604800 / $coingentime)}</td>
                <td>{$lastblocksbytime.7daystotal}</td>
                <td>{$lastblocksbytime.7daysvalid}</td>
                <td>{$lastblocksbytime.7daysorphan}</td>
                <td>
                {if $lastblocksbytime.7daysvalid > 0}
                  {($lastblocksbytime.7daysdifficulty / $lastblocksbytime.7daysvalid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$lastblocksbytime.7daysestimatedshares}</td>
                <td>{$lastblocksbytime.7daysshares}</td>
                <td>
                {if $lastblocksbytime.7daysestimatedshares > 0}
                  <font color="{if (($lastblocksbytime.7daysshares / $lastblocksbytime.7daysestimatedshares * 100) <= 100)}green{else}red{/if}">{($lastblocksbytime.7daysshares / $lastblocksbytime.7daysestimatedshares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$lastblocksbytime.7daysamount}</td>
                <td>{($lastblocksbytime.7daystotal|default:"0.00" / (604800 / $coingentime)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 4 weeks</th>
                <td>{(2419200 / $coingentime)}</td>
                <td>{$lastblocksbytime.4weekstotal}</td>
                <td>{$lastblocksbytime.4weeksvalid}</td>
                <td>{$lastblocksbytime.4weeksorphan}</td>
                <td>
                {if $lastblocksbytime.4weeksvalid > 0}
                  {($lastblocksbytime.4weeksdifficulty / $lastblocksbytime.4weeksvalid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$lastblocksbytime.4weeksestimatedshares}</td>
                <td>{$lastblocksbytime.4weeksshares}</td>
                <td>
                {if $lastblocksbytime.4weeksestimatedshares > 0}
                  <font color="{if (($lastblocksbytime.4weeksshares / $lastblocksbytime.4weeksestimatedshares * 100) <= 100)}green{else}red{/if}">{($lastblocksbytime.4weeksshares / $lastblocksbytime.4weeksestimatedshares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$lastblocksbytime.4weeksamount}</td>
                <td>{($lastblocksbytime.4weekstotal|default:"0.00" / (2419200 / $coingentime)  * 100)|number_format:"2"}%</td>
              </tr>
              <tr>
                <th style="padding-left: 15px">last 12 month</th>
                <td>{(29030400 / $coingentime)}</td>
                <td>{$lastblocksbytime.12monthtotal}</td>
                <td>{$lastblocksbytime.12monthvalid}</td>
                <td>{$lastblocksbytime.12monthorphan}</td>
                <td>
                {if $lastblocksbytime.12monthvalid > 0}
                  {($lastblocksbytime.12monthdifficulty / $lastblocksbytime.12monthvalid)|number_format:"4"}
                {else}
                  0
                {/if}
                </td>
                <td>{$lastblocksbytime.12monthestimatedshares}</td>
                <td>{$lastblocksbytime.12monthshares}</td>
                <td>
                {if $lastblocksbytime.12monthestimatedshares > 0}
                  <font color="{if (($lastblocksbytime.12monthshares / $lastblocksbytime.12monthestimatedshares * 100) <= 100)}green{else}red{/if}">{($lastblocksbytime.12monthshares / $lastblocksbytime.12monthestimatedshares * 100)|number_format:"2"}%</font></b>
                {else}
                  0.00%
                {/if}
                </td>
                <td>{$lastblocksbytime.12monthamount}</td>
                <td>{($lastblocksbytime.12monthtotal|default:"0.00" / (29030400 / $coingentime)  * 100)|number_format:"2"}%</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="panel-footer">
        {if $global.config.payout_system != 'pps'}<ul><li>note: round earnings are not credited until <font color="orange">{$global.confirmations}</font> confirms.</li></ul>{/if}
      </div>
      <!-- /.panel -->
    </div>
  <!-- /.col-lg-12 -->
  </div>
</div>
