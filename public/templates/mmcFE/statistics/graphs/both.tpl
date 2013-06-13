{if is_array($YOURHASHRATES) && is_array($POOLHASHRATES)}
<div class="block_content tab_content" id="both" style="padding-left:30px;">
{foreach from=array('area','pie') item=chartType}
  <table width="60%" class="stats" rel="{$chartType}">
    <caption>Your vs Pool Hashrate</caption>
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
        <th scope="row">{$smarty.session.USERDATA.username}</th>
{for $i=date('G') to 23}
        <td>{$YOURHASHRATES.$i|default:"0"}</td>
{/for}
{for $i=0 to date('G', time() - 60 * 60)}
        <td>{$YOURHASHRATES.$i|default:"0"}</td>
{/for}
      </tr>
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
  <br />
{/foreach}
</div>
{else}
<p><li>No shares available to start calculations</li></p>
{/if}
