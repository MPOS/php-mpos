{if is_array($YOURHASHRATES)}
<div class="block_content tab_content" id="mine" style="padding-left:30px;">
  <table width="60%" class="stats" rel="area">
    <caption>Your Hashrate</caption>
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
    </tbody>
  </table>
</div>
{/if}
