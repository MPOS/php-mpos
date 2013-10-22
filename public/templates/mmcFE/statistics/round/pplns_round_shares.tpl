{include file="global/block_header.tpl" ALIGN="right" BLOCK_HEADER="PPLNS Round Shares" }
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">        
        <th class="center">Rank</th>
        <th class="left" scope="col">User Name</th>
        <th class="right" scope="col">Valid</th>
        <th class="right" scope="col">Invalid</th>
        <th class="right" scope="col">Invalid %</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $PPLNSROUNDSHARES}
      <tr{if $GLOBAL.userdata.username|default:"" == $PPLNSROUNDSHARES[contrib].username}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td class="center">{$rank++}</td>
        <td>{if $PPLNSROUNDSHARES[contrib].is_anonymous|default:"0" == 1}anonymous{else}{$PPLNSROUNDSHARES[contrib].username|escape}{/if}</td>
        <td class="right">{$PPLNSROUNDSHARES[contrib].pplns_valid|number_format}</td>
        <td class="right">{$PPLNSROUNDSHARES[contrib].pplns_invalid|number_format}</td>
	<td class="right">{if $PPLNSROUNDSHARES[contrib].pplns_invalid > 0 && $PPLNSROUNDSHARES[contrib].pplns_valid > 0}{($PPLNSROUNDSHARES[contrib].pplns_invalid / $PPLNSROUNDSHARES[contrib].pplns_valid * 100)|number_format:"2"|default:"0"}{else}0.00{/if}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

