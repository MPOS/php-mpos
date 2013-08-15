{if is_array($YOURHASHRATES) && is_array($POOLHASHRATES)}
<div class="block_content tab_content" id="both" style="padding-left:30px;">
  <table width="60%" class="stats" rel="area">
    <caption>Your vs Pool Hashrate</caption>
    <thead>
      <tr>
        <td></td>
{foreach $YOURHASHRATES as $hour=>$hashrate}
        <th scope="col">{$hour|default:"0"}:00</th>
{/foreach}
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">{$smarty.session.USERDATA.username}</th>
{foreach $YOURHASHRATES as $hour=>$hashrate}
        <td>{$hashrate|default:"0"}</td>
{/foreach}
      </tr>
      <tr>
        <th scope="row">Pool</th>
{foreach $POOLHASHRATES as $hour=>$hashrate}
        <td>{$hashrate|default:"0"}</td>
{/foreach}
      </tr>
    </tbody>
  </table>
  <br />
</div>
{/if}
