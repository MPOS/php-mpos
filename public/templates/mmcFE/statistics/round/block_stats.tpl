{include file="global/block_header.tpl" ALIGN="left" BLOCK_STYLE="width: 100%" BLOCK_HEADER="Block Stats"  STYLE="padding-left:5px;padding-right:5px;"} 
<table align="left" width="100%" border="0" style="font-size:13px;"><tbody><tr><td class="left">
  <table align="left" width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Name</th>
        <th scope="col">Value</th>
      </tr>
    </thead>
    <tbody>
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
  </table></td>
  <td class="right">
  <form action="{$smarty.server.PHP_SELF}" method="POST" id='height'>
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="{$smarty.request.action}">
  <input type="text" class="pin" name="height" value="{$smarty.request.height|default:"%"}">
  <input type="submit" class="submit small" value="Search">
</form></td></tr>
      <tr>
        <td class="left">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&prev=1"><img src="{$PATH}/images/prev.png" /></a>
        </td>
        <td class="right">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={$BLOCKDETAILS.height}&next=1"><img src="{$PATH}/images/next.png" /></a>
        </td>
      </tr>
</tbody></table>
{include file="global/block_footer.tpl"}
