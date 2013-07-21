{include file="global/block_header.tpl" BLOCK_HEADER="Pool Donors"}
<center>
{include file="global/pagination.tpl"}
<table width="500px" class="pagesort">
  <thead>
    <tr>
      <th>Name</th>
      <th class="center">%</th>
      <th class="right">{$GLOBAL.config.currency} Total</th>
    </tr>
  </thead>
  <tbody>
{section name=donor loop=$DONORS}
    <tr>
      <td>{if $DONORS[donor].is_anonymous|default:"0" == 1}anonymous{else}{$DONORS[donor].username}{/if}</td>
      <td class="center">{$DONORS[donor].donate_percent}</td>
      <td class="right">{$DONORS[donor].donation|number_format:"2"}</td>
    </tr>
{sectionelse}
    <tr>
      <td class="center" colspan="3">No confirmed donations yet, please be patient!</td>
    </tr>
{/section}
  </tbody>
</table>
</center>
{include file="global/block_footer.tpl"}
