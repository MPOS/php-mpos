{include file="global/block_header.tpl" BLOCK_HEADER="Your Average Hourly Hash Rate" BUTTONS=array(mine,pool,both)}
<div class="block_content tab_content" id="mine" style="padding-left:30px;">
  <table class="stats" rel="area" cellpadding="0">
    <caption>Your Hashrate&nbsp;</caption>
    <thead>
      <tr>
        <td></td>
{section hashrate $YOURHASHRATES}
        <th scope="col">{$YOURHASHRATES[hashrate].hour}</th>
{/section}
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">{$GLOBAL.USERDATA.username}</th>
{section hashrate $YOURHASHRATES}
        <td>{$YOURHASHRATES[hashrate].hashrate|number_format}</td>
{/section}
      </tr>
    </tbody>
  </table>
</div>
{include file="global/block_footer.tpl"}
