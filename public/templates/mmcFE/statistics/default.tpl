{include file="global/block_header.tpl" BLOCK_HEADER="Pool Statistics"}
<table class="" width="50%" style="font-size:14px;">
  <tbody>
  <tr>
    <td class="leftheader">Pool Hash Rate</td>
    <td>{$GLOBAL.hashrate|number_format:"3"} {$GLOBAL.hashunits.pool}</td>
  </tr>
  <tr>
    <td class="leftheader">Current Total Miners</td>
    <td>{$GLOBAL.workers}</td>
  </tr>
  <tr>
    <td class="leftheader">Current Block</td>
    {if ! $GLOBAL.website.blockexplorer.disabled}
      <td><a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCKHASH}" target="_new">{$CURRENTBLOCK}</a></td>
    {else}
      <td>{$CURRENTBLOCK}</td>
    {/if}
  </tr>
  <tr>
    <td class="leftheader">Current Difficulty</td>
      {if ! $GLOBAL.website.chaininfo.disabled}
        <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new">{$DIFFICULTY}</a></td>
      {else}
        <td>{$DIFFICULTY}</td>
      {/if}
  </tr>
  </tbody>
</table>
{if !$GLOBAL.website.api.disabled}<li>These stats are also available in JSON format <a href="{$smarty.server.PHP_SELF}?page=api&action=public" target="_api">HERE</a></li>{/if}
{include file="global/block_footer.tpl"}
