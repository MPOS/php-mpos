<table width="70%" class="stats" rel="line">
  <caption>Block Shares</caption> 
  <thead>
    <tr>
{section block $BLOCKSFOUND step=-1 max=20}
      <th scope="col">{$BLOCKSFOUND[block].height}</th>
{/section}
    </th>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Expected</th>
{section block $BLOCKSFOUND step=-1 max=20}
      <td>{round(pow(2,32 - $GLOBAL.config.targetdiff) * $BLOCKSFOUND[block].difficulty)}</td>
{/section}
    </tr>
    <tr>
      <th scope="row">Actual</th>
{section block $BLOCKSFOUND step=-1 max=20}
      <td>{$BLOCKSFOUND[block].shares}</td>
{/section}
   </tr>
  </tbody>
</table>
<center><br>
<p style="padding-left:30px; padding-redight:30px; font-size:10px;">
The graph above illustrates N shares to find a block vs. E Shares expected to find a block based on
target and network difficulty and assuming a zero variance scenario.
</p></center>

<center>
  <table width="100%" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th class="center">Block</th>
        <th class="center">Validity</th>
        <th>Finder</th>
        <th class="center">Time</th>
        <th class="right">Difficulty</th>
        <th class="right">Expected Shares</th>
        <th class="right">Actual Shares</th>
        <th class="right">Percentage</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section block $BLOCKSFOUND}
      <tr class="{cycle values="odd,even"}">
        <td class="center"><a href="{$GLOBAL.blockexplorer}{$BLOCKSFOUND[block].height}" target="_blank">{$BLOCKSFOUND[block].height}</a></td>
        <td class="center">
        {if $BLOCKSFOUND[block].confirmations >= $GLOBAL.confirmations}
          <font color="green">Confirmed</font>
        {else if $BLOCKSFOUND[block].confirmations == -1}
          <font color="red">Orphan</font>
        {else}{$GLOBAL.confirmations - $BLOCKSFOUND[block].confirmations} left{/if}</td>
        <td>{$BLOCKSFOUND[block].finder|default:"unknown"}</td>
        <td class="center">{$BLOCKSFOUND[block].time|date_format:"%d/%m %H:%M:%S"}</td>
        <td class="right">{$BLOCKSFOUND[block].difficulty|number_format:"2"}</td>
        <td class="right">{(65536 * $BLOCKSFOUND[block].difficulty)|number_format}</td>
        <td class="right">{$BLOCKSFOUND[block].shares|number_format}</td>
        <td class="right">{($BLOCKSFOUND[block].shares / (65536 * $BLOCKSFOUND[block].difficulty) * 100)|number_format:"2"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
<ul>
  <li>Note: <font color="orange">Round Earnings are not credited until {$GLOBAL.confirmations} confirms.</font></li>
</ul>
