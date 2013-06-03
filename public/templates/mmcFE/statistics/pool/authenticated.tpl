{include file="global/block_header.tpl" BLOCK_HEADER="Pool Statistics" BLOCK_STYLE="clear:none;"}

{include file="statistics/pool/contributors_shares.tpl"}

{include file="statistics/pool/contributors_hashrate.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Server Stats" BLOCK_STYLE="clear:all;" STYLE="padding-left:5px;padding-right:5px;"}
<table class="" width="100%" style="font-size:13px;">
  <tbody>
    <tr>
      <td class="leftheader">Pool Hash Rate</td>
      <td>{$GLOBAL.hashrate / 1000} Mhash/s</td>
    </tr>
    <tr>
      <td class="leftheader">Current Workers Mining</td>
      <td>{$GLOBAL.workers}</td>
    </tr>
    <tr>
      <td class="leftheader">Next Network Block</td>
      <td><a href="{$GLOBAL.blockexplorer}{$CURRENTBLOCK + 1}" target="_new">{$CURRENTBLOCK + 1}</a> &nbsp;&nbsp;<font size="1"> (Current: <a href="{$GLOBAL.blockexplorer}{$CURRENTBLOCK}" target="_new">{$CURRENTBLOCK})</a></font></td>
    </tr>
    <tr>
      <td class="leftheader">Last Block Found</td>
      <td><a href="{$GLOBAL.blockexplorer}{$LASTBLOCK}" target="_new">{$LASTBLOCK|default:"0"}</a></td>
    </tr>
    <tr>
      <td class="leftheader">Current Difficulty</td>
      <td><a href="{$GLOBAL.chaininfo}" target="_new"><font size="2">{$DIFFICULTY}</font></a></td>
    </tr>
    <tr>
      <td class="leftheader">Est. Avg. Time per Round</td>
      <td>{$ESTTIME|seconds_to_words}</td>
    </tr>
    <tr>
      <td class="leftheader">Est. Avg. Shares per Round</td>
      <td>{($ESTTIME * $GLOBAL.sharerate)|number_format:"0"}</td>
    </tr>
    <tr>
      <td class="leftheader">Time Since Last Block</td>
      <td>{$TIMESINCELAST|seconds_to_words}</td>
    </tr>
  </tbody>
</table>
{include file="global/block_footer.tpl"}


{include file="statistics/blocks/blocks_found.tpl"}

{include file="global/block_footer.tpl"}
