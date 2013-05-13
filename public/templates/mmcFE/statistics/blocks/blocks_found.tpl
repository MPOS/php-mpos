{include file="global/block_header.tpl" BLOCK_HEADER="Last 10 Blocks Found" BLOCK_STYLE="clear:none;" BUTTONS=array(More)}
<center>
  <table class="stats_lastblocks" width="100%" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th scope="col" align="left">Block</th>
        <th scope="col" align="left">Validity</th>
        <th scope="col" align="left">Finder</th>
        <th scope="col" align="left">Date / Time</th>
        <th scope="col" align="left">Difficulty</th>
        <th scope="col" align="left">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section block $BLOCKSFOUND}
      <tr class="{cycle values="odd,even"}">
        <td>{$BLOCKSFOUND[block].height}</td>
        <td>{if $BLOCKSFOUND[block].confirmations >= 120}<font color="green">Confirmed</font>{else}{120 - $BLOCKSFOUND[block].confirmations} left{/if}</td>
        <td>{$BLOCKSFOUND[block].finder|default:"unknown"}</td>
        <td>{$BLOCKSFOUND[block].time|date_format:"%d/%m/%Y %H:%M:%S"}</td>
        <td>{$BLOCKSFOUND[block].difficulty|number_format:"8"}</td>
        <td>{$BLOCKSFOUND[block].shares|number_format}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
<ul>
  <li>Note: <font color="orange">Round Earnings are not credited until 120 confirms.</font></li>
</ul>
{include file="global/block_footer.tpl"}
