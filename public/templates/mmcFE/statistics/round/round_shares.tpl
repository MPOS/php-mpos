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
{assign var=listed value=0}
{section contrib $ROUNDSHARES}
      <tr{if $GLOBAL.userdata.username == $ROUNDSHARES[contrib].username}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td class="center">{$rank++}</td>
        <td>{if $ROUNDSHARES[contrib].is_anonymous|default:"0" == 1}anonymous{else}{$ROUNDSHARES[contrib].username|escape}{/if}</td>
        <td class="right">{$ROUNDSHARES[contrib].valid|number_format}</td>
        <td class="right">{$ROUNDSHARES[contrib].invalid|number_format}</td>
	<td class="right">{($ROUNDSHARES[contrib].invalid / $ROUNDSHARES[contrib].valid * 100)|number_format:"2"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}
