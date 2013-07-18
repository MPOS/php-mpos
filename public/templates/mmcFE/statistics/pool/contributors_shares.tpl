{include file="global/block_header.tpl" ALIGN="right" BLOCK_HEADER="Top Share Contributers"}
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Rank</th>
        <th scope="col">User Name</th>
        <th class="right" scope="col">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section shares $CONTRIBSHARES}
      <tr{if $GLOBAL.userdata.username == $CONTRIBSHARES[shares].account}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td>{$rank++}</td>
        <td>{if $CONTRIBSHARES[shares].is_anonymous|default:"0" == 1}anonymous{else}{$CONTRIBSHARES[shares].account|escape}{/if}</td>
        <td class="right">{$CONTRIBSHARES[shares].shares|number_format}</td>
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:""}
      <tr style="background-color:#99EB99;">
        <td>n/a</td>
        <td>{$GLOBAL.userdata.username|escape}</td>
        <td class="right">{$GLOBAL.userdata.shares.valid|number_format}</td>
      </tr>
{/if}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}
