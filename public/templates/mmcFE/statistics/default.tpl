{include file="global/block_header.tpl" BLOCK_HEADER="Pool Statistics"}
<table class="" width="50%" style="font-size:14px;">
  <tbody>
  <tr>
    <td class="leftheader">Pool Hash Rate</td>
    <td>{$GLOBAL.hashrate / 1000} Mhash/s</td>
  </tr>
  <tr>
    <td class="leftheader">Current Total Miners</td>
    <td>{$GLOBAL.workers}</td>
  </tr>
  <tr>
    <td class="leftheader">Current Block</td>
    <td><a href="http://explorer.litecoin.net/search?q={$CURRENTBLOCK}" target="_new">{$CURRENTBLOCK}</a></td>
  </tr>
  <tr>
    <td class="leftheader">Current Difficulty</td>
    <td><a href="http://allchains.info/" target="_new">{$CURRENTDIFFICULTY}</a></td>
  </tr>
  </tbody>
</table>
{include file="global/block_footer.tpl"}
