<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-tasks fa-fw"></i> Last {$BLOCKLIMIT} Blocks Found
      </div>
      <div class="panel-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Block</th>
                <th>Validity</th>
                <th>Finder</th>
                <th>Time</th>
                <th>Difficulty</th>
                <th>Amount</th>
                <th>Expected Shares</th>
                {if $GLOBAL.config.payout_system == 'pplns'}<th>PPLNS Shares</th>{/if}
                <th>Actual Shares</th>
                <th style="padding-right: 25px;">Percentage</th>
              </tr>
            </thead>
            <tbody>
              {assign var=count value=0}
              {assign var=totalexpectedshares value=0}
              {assign var=totalshares value=0}
              {assign var=pplnsshares value=0}
              {section block $BLOCKSFOUND}
              <tr>
              {assign var="totalshares" value=$totalshares+$BLOCKSFOUND[block].shares}
              {assign var="count" value=$count+1}
              {if $GLOBAL.config.payout_system == 'pplns'}{assign var="pplnsshares" value=$pplnsshares+$BLOCKSFOUND[block].pplns_shares}{/if}
              {if ! $GLOBAL.website.blockexplorer.disabled}
                <td><a href="{$smarty.server.SCRIPT_NAME}?page=statistics&action=round&height={$BLOCKSFOUND[block].height}">{$BLOCKSFOUND[block].height}</a></td>
              {else}
                <td>{$BLOCKSFOUND[block].height}</td>
              {/if}
              <td>
              {if $BLOCKSFOUND[block].confirmations >= $GLOBAL.confirmations}
                <span class="label label-success">Confirmed</span>
              {else if $BLOCKSFOUND[block].confirmations == -1}
                <span class="label label-danger">Orphan</span>
              {else}
                <span class="label label-warning">{$GLOBAL.confirmations - $BLOCKSFOUND[block].confirmations} left</span>
              {/if}
              </td>
                <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$BLOCKSFOUND[block].finder|default:"unknown"|escape}{/if}</td>
                <td>{$BLOCKSFOUND[block].time|date_format:"%d/%m %H:%M:%S"}</td>
                <td>{$BLOCKSFOUND[block].difficulty|number_format:"2"}</td>
                <td>{$BLOCKSFOUND[block].amount|number_format:"2"}</td>
                <td>
                {assign var="totalexpectedshares" value=$totalexpectedshares+$BLOCKSFOUND[block].estshares}
                  {$BLOCKSFOUND[block].estshares|number_format}
                </td>
                {if $GLOBAL.config.payout_system == 'pplns'}
                <td>{$BLOCKSFOUND[block].pplns_shares|number_format}</td>
                {/if}
                <td>{$BLOCKSFOUND[block].shares|number_format}</td>
                <td style="padding-right: 25px;">
                  {math assign="percentage" equation="shares / estshares * 100" shares=$BLOCKSFOUND[block].shares|default:"0" estshares=$BLOCKSFOUND[block].estshares}
                  <font color="{if ($percentage <= 100)}green{else}red{/if}">{$percentage|number_format:"2"}</font>
                </td>
              </tr>
              {/section}
              <tr>
                <td colspan="6"><b>Totals</b></td>
                <td>{$totalexpectedshares|number_format}</td>
                {if $GLOBAL.config.payout_system == 'pplns'}
                <td>{$pplnsshares|number_format}</td>
                {/if}
                <td>{$totalshares|number_format}</td>
                <td style="padding-right: 25px;">{if $count > 0}<font color="{if (($totalshares / $totalexpectedshares * 100) <= 100)}green{else}red{/if}">{($totalshares / $totalexpectedshares * 100)|number_format:"2"}</font>{else}0{/if}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="panel-footer">
        <h6>{if $GLOBAL.config.payout_system != 'pps'}Round Earnings are not credited until <font color="orange">{$GLOBAL.confirmations}</font> confirms.{/if}</h6>
      </div>
    </div>
  </div>
</div>
