<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info table-responsive">
      <div class="panel-heading">
        <i class="fa fa-bar-chart fa-fw"></i> Block Statistics
      </div>
      <ul class="pager">
        <li class="previous">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={$BLOCKDETAILS.height}&prev=1">&larr; Prev</a>
        </li>
        <li class="next">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={$BLOCKDETAILS.height}&next=1">Next &rarr;</a>
        </li>
      </ul>
      <table>
        <thead>
          <tr>
        <th colspan="4">Block Statistics</th>
        <th colspan="4">PPLNS Round Statistics</th>
          </tr>
        </thead>
        <tbody>
          <tr class="odd">
            <td>ID</td>
            <td>{$BLOCKDETAILS.id|number_format:"0"|default:"0"}</td>
            <td>Height</td>
            {if ! $GLOBAL.website.blockexplorer.disabled}
            <td><a href="{$GLOBAL.website.blockexplorer.url}{$BLOCKDETAILS.blockhash}" target="_new">{$BLOCKDETAILS.height|number_format:"0"|default:"0"}</a></td>
            {else}
            <td>{$BLOCKDETAILS.height|number_format:"0"|default:"0"}</td>
            {/if}
            <td>PPLNS Shares</td>
            <td>{$PPLNSSHARES|number_format:"0"|default:"0"}</td>
            <td>Estimated Shares</td>
            <td>{$BLOCKDETAILS.estshares|number_format|default:"0"}</td>
          </tr>
          <tr class="odd">
            <td>Amount</td>
            <td>{$BLOCKDETAILS.amount|default:"0"}</td>
            <td>Confirmations</td>
            <td>{if $BLOCKDETAILS.confirmations >= $GLOBAL.confirmations}
            <font color="green">Confirmed</font>
            {else if $BLOCKDETAILS.confirmations == -1}
            <font color="red">Orphan</font>
            {else if $BLOCKDETAILS.confirmations == 0}0
            {else}{($GLOBAL.confirmations - $BLOCKDETAILS.confirmations)|default:"0"} left{/if}</td>
            <td>Block Average</td>
            <td>{$BLOCKAVERAGE|number_format:"0"|default:"0"}</td>
            <td>Average Efficiency</td>
            <td>{math assign="percentage2" equation=(($BLOCKDETAILS.estshares / $BLOCKAVERAGE) * 100)}<font color="{if ($percentage2 >= 100)}green{else}red{/if}">{$percentage2|number_format:"2"} %</font></td>
          </tr>
          <tr class="odd">
            <td>Difficulty</td>
            <td>{$BLOCKDETAILS.difficulty|default:"0"}</td>
            <td>Time</td>
            <td>{$BLOCKDETAILS.time|default:"0"}</td>
            <td>Target Rounds</td>
            <td>{$BLOCKAVGCOUNT|number_format:"0"|default:"0"}</td>
            <td>Target Variance</td>
            <td>{math assign="percentage" equation=(($BLOCKDETAILS.estshares / $PPLNSSHARES) * 100)}<font color="{if ($percentage >= 100)}green{else}red{/if}">{$percentage|number_format:"2"} %</font></td>
          </tr>
          <tr class="odd">
            <td>Shares</td>
            <td>{$BLOCKDETAILS.shares|number_format:"0"|default:"0"}</td>
            <td>Finder</td>
            <td>{$BLOCKDETAILS.finder|default:"unknown"}</td>
            <td>Seconds This Round</td>
            <td>{$BLOCKDETAILS.round_time|number_format:"0"|default:"0"}</td>
            <td>Round Variance</td>
            <td>{math assign="percentage1" equation=(($BLOCKDETAILS.shares / $PPLNSSHARES) * 100)}<font color="{if ($percentage1 >= 100)}green{else}red{/if}">{$percentage1|number_format:"2"} %</font></td>
          </tr>
        </tbody>
      </table>
  
      <footer>
        <form action="{$smarty.server.SCRIPT_NAME}" method="POST" id='search' role="form">
          <input type="hidden" name="page" value="{$smarty.request.page|escape}">
          <input type="hidden" name="action" value="{$smarty.request.action|escape}">
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" name="search" value="{$smarty.request.height|default:"%"|escape}">
              <span class="input-group-btn">
                <button class="btn btn-sm" type="submit" value="Search"><i class="fa fa-search"></i></button>
              </span>
          </div>
        </form>
      </footer>
    </div>
  </div>
</div>
