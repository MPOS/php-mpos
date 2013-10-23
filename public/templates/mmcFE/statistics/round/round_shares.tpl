{include file="global/block_header.tpl" ALIGN="left" BLOCK_HEADER="Round Shares" }
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
{section contrib $ROUNDSHARES}
      <tr{if $GLOBAL.userdata.username|default:"" == $ROUNDSHARES[contrib].username} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td class="center">{$rank++}</td>
        <td>{if $ROUNDSHARES[contrib].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$ROUNDSHARES[contrib].username|escape}{/if}</td>
        <td class="right">{$ROUNDSHARES[contrib].valid|number_format}</td>
        <td class="right">{$ROUNDSHARES[contrib].invalid|number_format}</td>
	<td class="right">{if $ROUNDSHARES[contrib].invalid > 0 }{($ROUNDSHARES[contrib].invalid / $ROUNDSHARES[contrib].valid * 100)|number_format:"2"|default:"0"}{else}0.00{/if}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

