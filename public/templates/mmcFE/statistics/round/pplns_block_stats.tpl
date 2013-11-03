{include file="global/block_header.tpl" ALIGN="left" BLOCK_STYLE="width: 100%" BLOCK_HEADER="Block Stats"  STYLE="padding-left:5px;padding-right:5px;"}
<br>
<center>
  <form action="{$smarty.server.PHP_SELF}" method="POST" id='search'>
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="text" class="pin" name="search" value="{$smarty.request.height|default:"%"|escape}">
  <input type="submit" class="submit small" value="Search">
</form>
</center> 
<table align="left" width="100%" border="0" style="font-size:13px;"><tbody>
      <tr>
        <td class="left">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&prev=1"><img src="{$PATH}/images/prev.png" /></a>
        </td>
        <td class="right">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&next=1"><img src="{$PATH}/images/next.png" /></a>
        </td>
      </tr>
<tr><td class="left">
  <table align="left" width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th scope="col" colspan="2">Block Round Statistics</th>
      </tr>
    </thead>
    <tbody>
      <tr class="odd">
        <td>ID</td>
        <td>{$BLOCKDETAILS.id|number_format:"0"|default:"0"}</td>
      </tr>
      <tr class="even">
        <td>Height</td>
	{if ! $GLOBAL.website.blockexplorer.disabled}
	<td><a href="{$GLOBAL.website.blockexplorer.url}{$BLOCKDETAILS.blockhash}" target="_new">{$BLOCKDETAILS.height}</a></td>
	{else}
	<td>{$BLOCKDETAILS.height}</td>
	{/if}
      </tr>
      <tr class="odd">
        <td>Amount</td>
        <td>{$BLOCKDETAILS.amount|default:"0"}</td>
      </tr>
      <tr class="even">
        <td>Confirmations</td>
        <td>{if $BLOCKDETAILS.confirmations >= $GLOBAL.confirmations}
          <font color="green">Confirmed</font>
        {else if $BLOCKDETAILS.confirmations == -1}
          <font color="red">Orphan</font>
        {else if $BLOCKDETAILS.confirmations == 0}0
        {else}{($GLOBAL.confirmations - $BLOCKDETAILS.confirmations)|default:"0"} left{/if}</td>
      </tr>
      </tr>
      <tr class="odd">
        <td>Difficulty</td>
        <td>{$BLOCKDETAILS.difficulty|default:"0"}</td>
      </tr>
      <tr class="even">
        <td>Time</td>
        <td>{$BLOCKDETAILS.time|default:"0"}</td>
      </tr>
      <tr class="odd">
        <td>Shares</td>
        <td>{$BLOCKDETAILS.shares|number_format:"0"|default:"0"}</td>
      </tr>
      <tr class="even">
        <td>Finder</td>
        <td>{$BLOCKDETAILS.finder|default:"0"}</td>
      </tr>
    </tbody>
  </table></td>
{assign var=percentage value=0}
{assign var=percentage1 value=0}
{assign var=percentage2 value=0}
  <td class="left"><table align="left" width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th scope="col" colspan="2">PPLNS Round Statistics</th>
      </tr>
    </thead>
    <tbody>
      <tr class="odd">
        <td>PPLNS Shares</td>
        <td>{$PPLNSSHARES|number_format:"0"|default:"0"}</td>
      </tr>
      <tr class="even">
        <td>Estimated Shares</td>
        <td>{$BLOCKDETAILS.estshares|number_format|default:"0"}</td>
      </tr>
      <tr class="odd">
        <td>Target Variance</td>
        <td>{if $PPLNSSHARES > 0}{math assign="percentage" equation=(($BLOCKDETAILS.estshares / $PPLNSSHARES) * 100)}{/if}<font color="{if ($percentage >= 100)}green{else}red{/if}">{$percentage|number_format:"2"} %</font></td>
      </tr>
      <tr class="even">
        <td>Block Average</td>
        <td>{$BLOCKAVERAGE|number_format:"0"|default:"0"}</td>
      </tr>
      <tr class="odd">
        <td>Average Efficiency</td>
        <td>{if $BLOCKAVERAGE > 0 && $BLOCKDETAILS.estshares > 0}{math assign="percentage2" equation=(($BLOCKDETAILS.estshares / $BLOCKAVERAGE) * 100)}{/if}<font color="{if ($percentage2 >= 100)}green{else}red{/if}">{$percentage2|number_format:"2"} %</font></td>
      </tr>
      <tr class="even">
        <td>Target Rounds</td>
        <td>{$BLOCKAVGCOUNT|number_format:"0"|default:"0"}</td>
      </tr>
      <tr class="odd">
        <td>Seconds This Round</td>
        <td>{$BLOCKDETAILS.round_time|number_format:"0"|default:"0"}</td>
      </tr>
     <tr class="even">
        <td>Round Variance</td>
        <td>{if $PPLNSSHARES > 0}{math assign="percentage1" equation=(($BLOCKDETAILS.shares / $PPLNSSHARES) * 100)}{/if}<font color="{if ($percentage1 >= 100)}green{else}red{/if}">{$percentage1|number_format:"2"} %</font></td>
      </tr>
    </tbody>
  </table></td></tr>
</tbody></table>
{include file="global/block_footer.tpl"}

