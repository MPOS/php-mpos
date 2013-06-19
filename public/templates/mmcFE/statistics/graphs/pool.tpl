{if is_array($POOLHASHRATES)}
<div class="block_content tab_content" id="pool" style="padding-left:30px;">
  <table width="60%" class="stats" rel="area">
    <caption>Pool Hashrate</caption>
    <thead>
      <tr>
        <td></td>
{for $i=date('G') to 23}
        <th scope="col">{$i}:00</th>
{/for}
{for $i=0 to date('G', time () - 60 * 60)}
        <th scope="col">{$i}:00</th>
{/for}
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">Pool</th>
{for $i=date('G') to 23}
        <td>{$POOLHASHRATES.$i|default:"0"}</td>
{/for}
{for $i=0 to date('G', time() - 60 * 60)}
        <td>{$POOLHASHRATES.$i|default:"0"}</td>
{/for}
      </tr>
    </tbody>
  </table>
</div>
{else}
<p><li>No shares available to start calculations</li></p>
{/if}
