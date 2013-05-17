{include file="global/block_header.tpl" ALIGN="left" BLOCK_HEADER="Top Hashrate Contributers"}
<center>
  <table width="100%" border="0" style="font-size:13px;" class="sortable">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Rank</th>
        <th align="left" scope="col">User Name</th>
        <th align="left" scope="col">KH/s</th>
        <th align="left">Ł/Day<font size="1"> (est)</font></th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section contrib $CONTRIBHASHES}
      <tr class="{cycle values="odd,even"}">
        <td>{$rank++}</td>
        <td>{$CONTRIBHASHES[contrib].account}</td>
        <td>{$CONTRIBHASHES[contrib].hashrate|number_format}</td>
        <td>{math equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24),3)" diff=$DIFFICULTY reward=$REWARD hashrate=$CONTRIBHASHES[contrib].hashrate}</td>
      </tr>
{/section}
    </tbody>
  </table>
  <div id="pagination" class="pagination"></div>
</center>
{include file="global/block_footer.tpl"}
