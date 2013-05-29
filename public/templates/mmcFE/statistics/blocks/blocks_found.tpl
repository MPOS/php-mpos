{include file="global/block_header.tpl" BLOCK_HEADER="Last $BLOCKLIMIT Blocks Found" BLOCK_STYLE="clear:none;"}
<center>
  <table class="stats_lastblocks" width="100%" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th scope="col" align="left">Block</th>
        <th scope="col" align="left">Validity</th>
        <th scope="col" align="left">Finder</th>
        <th scope="col" align="left">Date / Time</th>
        <th class="right" scope="col">Difficulty</th>
        <th class="right" scope="col">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section block $BLOCKSFOUND}
      <tr class="{cycle values="odd,even"}">
        <td><a href="{$GLOBAL.blockexplorer}{$BLOCKSFOUND[block].height}" target="_blank">{$BLOCKSFOUND[block].height}</a></td>
        <td>
        {if $BLOCKSFOUND[block].confirmations >= $GLOBAL.confirmations}
          <font color="green">Confirmed</font>
        {else if $BLOCKSFOUND[block].confirmations == -1}
          <font color="red">Orphan</font>
        {else}{$GLOBAL.confirmations - $BLOCKSFOUND[block].confirmations} left{/if}</td>
        <td>{$BLOCKSFOUND[block].finder|default:"unknown"}</td>
        <td>{$BLOCKSFOUND[block].time|date_format:"%d/%m/%Y %H:%M:%S"}</td>
        <td class="right">{$BLOCKSFOUND[block].difficulty|number_format:"8"}</td>
        <td class="right">{$BLOCKSFOUND[block].shares|number_format}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
<ul>
  <li>Note: <font color="orange">Round Earnings are not credited until {$GLOBAL.confirmations} confirms.</font></li>
</ul>
{include file="global/block_footer.tpl"}
