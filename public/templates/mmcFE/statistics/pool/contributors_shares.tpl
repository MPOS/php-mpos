{include file="global/block_header.tpl" ALIGN="right" BLOCK_HEADER="Top Share Contributers"}
<center>
  <table width="100%" border="0" style="font-size:13px;" class="sortable">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Rank</th>
        <th align="left" scope="col">User Name</th>
        <th align="left" scope="col">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section hashrate $CONTRIBSHARES}
      <tr class="{cycle values="odd,even"}">
        <td>{$rank++}</td>
        <td>{$CONTRIBSHARES[hashrate].account}</td>
        <td>{$CONTRIBSHARES[hashrate].shares|number_format}</td>
      </tr>
{/section}
    </tbody>
  </table>
  <div id="pagination" class="pagination"></div>
</center>
{include file="global/block_footer.tpl"}
