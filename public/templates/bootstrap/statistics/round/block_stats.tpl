<article class="module width_full">
  <header><h3>Block Statistics</h3></header>
  <table class="tablesorter">
    <tbody>
      <tr>
        <td align="left">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&prev=1"><i class="icon-left-open"></i></a>
        </td>
        <td colspan="7" align="right">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&next=1"><i class="icon-right-open"></i></a>
        </td>
      </tr>
      <tr class="odd">
        <td>ID</td>
        <td>{$BLOCKDETAILS.id|number_format:"0"|default:"0"}</td>
        <td>Height</td>
	{if ! $GLOBAL.website.blockexplorer.disabled}
      	<td><a href="{$GLOBAL.website.blockexplorer.url}{$BLOCKDETAILS.blockhash}" target="_new">{$BLOCKDETAILS.height|number_format:"0"|default:"0"}</a></td>
	{else}
      	<td>{$BLOCKDETAILS.height|number_format:"0"|default:"0"}</td>
	{/if}
        <td>Amount</td>
        <td>{$BLOCKDETAILS.amount|number_format|default:"0"}</td>
        <td>Confirmations</td>
        <td>{if $BLOCKDETAILS.confirmations >= $GLOBAL.confirmations}
          <font color="green">Confirmed</font>
        {else if $BLOCKDETAILS.confirmations == -1}
          <font color="red">Orphan</font>
        {else if $BLOCKDETAILS.confirmations == 0}0
        {else}{($GLOBAL.confirmations - $BLOCKDETAILS.confirmations)|default:"0"} left{/if}</td>
      </tr>
      <tr class="even">
        <td>Difficulty</td>
        <td>{$BLOCKDETAILS.difficulty|default:"0"}</td>
        <td>Time</td>
        <td>{$BLOCKDETAILS.time|default:"0"}</td>
        <td>Shares</td>
        <td>{$BLOCKDETAILS.shares|number_format:"0"|default:"0"}</td>
        <td>Finder</td>
        <td>{$BLOCKDETAILS.finder|default:"unknown"}</td>
      </tr>
    </tbody>
  </table>
  <footer>
    <div class="submit_link">
      <form action="{$smarty.server.SCRIPT_NAME}" method="POST" id='search'>
        <input type="hidden" name="page" value="{$smarty.request.page|escape}">
        <input type="hidden" name="action" value="{$smarty.request.action|escape}">
        <input type="text" class="pin" name="search" value="{$smarty.request.height|default:"%"|escape}">
        <input type="submit" value="Search" class="alt_btn">
      </form>
    </div>
  </footer>
</article>
