<article class="module width_full">
<header><h3>Pool Donors</h3></header>
<div class="module_content">
<center>
{include file="global/pagination.tpl"}
<table width="500px">
<thead>
<tr>
<th><u>Name</u></th>
<th><u>%</u></th>
<th><u>{$GLOBAL.config.currency} Total</u></th>
</tr>
</thead>
<br>
<tbody>
{section name=donor loop=$DONORS}
<tr>
<th>{if $DONORS[donor].is_anonymous|default:"0" == 1}anonymous{else}{$DONORS[donor].username}{/if}</th>
<th>{$DONORS[donor].donate_percent}</th>
<th>{$DONORS[donor].donation|number_format:"2"}</th>
</tr>
{sectionelse}
<tr>
<td class="center" colspan="3">No confirmed donations yet, please be patient!</td>
</tr>
{/section}
</tbody>
</table>
</center>
</div>
</article>
