<article class="module width_full">
  <header><h3>Block Statistics</h3></header>
  <table class="tablesorter">
    <tbody>
      <tr>
        <td class="left">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&prev=1"><i class="icon-left-open"></i></a>
        </td>
        <td class="right">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&next=1"><i class="icon-right-open"></i></a>
        </td>
      </tr>
      <tr class="odd">
        <td>ID</td>
        <td>{$BLOCKDETAILS.id|default:"0"}</td>
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
        <td>{$BLOCKDETAILS.confirmations|default:"0"}</td>
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
        <td>{$BLOCKDETAILS.shares|default:"0"}</td>
      </tr>
      <tr class="even">
        <td>Finder</td>
        <td>{$BLOCKDETAILS.finder|default:"0"}</td>
      </tr>
    </tbody>
  </table>
  <footer>
    <div class="submit_link">
      <form action="{$smarty.server.PHP_SELF}" method="POST" id='height'>
        <input type="hidden" name="page" value="{$smarty.request.page}">
        <input type="hidden" name="action" value="{$smarty.request.action}">
        <input type="text" class="pin" name="height" value="{$smarty.request.height|default:"%"}">
        <input type="submit" value="Search" class="alt_btn">
      </form>
    </div>
  </footer>
</article>
