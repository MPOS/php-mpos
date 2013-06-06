{include file="global/block_header.tpl" BLOCK_HEADER="Your Average Hourly Hash Rate" BUTTONS=array(mine,pool,both)}
{if is_array($YOURHASHRATES)}
<div class="block_content tab_content" id="mine" style="padding-left:30px;">
  <table class="stats" rel="area" cellpadding="0">
    <caption>Your Hashrate&nbsp;</caption>
    <thead>
      <tr>
        <td></td>
{for $i=date('G', time() - 60 * 60) to 23}
        <th scope="col">{$i}:00</th>
{/for}
{for $i=0 to date('G', time () - 2 * 60 * 60)}
        <th scope="col">{$i}:00</th>
{/for}
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">{$smarty.session.USERDATA.username}</th>
{for $i=date('G', time() - 60 * 60) to 23}
        <td>{$YOURHASHRATES.$i|default:"0"}</td>
{/for}
{for $i=0 to date('G', time() - 2 * 60 * 60)}
        <td>{$YOURHASHRATES.$i|default:"0"}</td>
{/for}
      </tr>
    </tbody>
  </table>
</div>
{else}
<p><li>No shares available to start calculations</li></p>
{/if}
{include file="global/block_footer.tpl"}
