{include file="global/block_header.tpl" ALIGN="left" BLOCK_STYLE="width: 100%" BLOCK_HEADER="Earnings Information"  STYLE="padding-left:5px;padding-right:5px;"} 
<form action="{$smarty.server.PHP_SELF}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
<table width="100%" border="0" style="font-size:13px;">
    <tbody>
      <tr>
        <td class="left">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={if is_array($REPORTDATA) && count($REPORTDATA) > ($BLOCKLIMIT - 1)}{$REPORTDATA[$BLOCKLIMIT - 1].height}{/if}&prev=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"><img src="{$PATH}/images/prev.png" /></a>
        </td>
        <td class="right">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&height={if is_array($REPORTDATA) && count($REPORTDATA) > 0}{$REPORTDATA[0].height}{/if}&next=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"><img src="{$PATH}/images/next.png" /></a>
        </td>
      </tr>
    </tbody>
  </table>
<table width="100%" border="0" style="font-size:13px;">
  <thead>
      <tr style="background-color:#B6DAFF;">
        <th class="center">Select User</th>
        <th class="center">Block Limit</th>
        <th class="center">Starting Block Height</th>
        <th class="center">Show Empty Rounds</th>
      </tr>
  </thead>
    <tbody>
      <tr>
        <td class="center">
            {html_options name="id" options=$USERLIST selected=$USERID|default:"0"}
        </td>
        <td class="center">
            <input size="12" type="text" name="limit" value="{$BLOCKLIMIT|default:"20"}" />
        </td>
        <td class="center">
            <input size="12" type="text" name="search" value="{$HEIGHT|default:"%"}">
        </td>
        <td class="center">
            <input type="checkbox" name="filter" value="1" id="filter" {if $FILTER}checked{/if} />
            <label for="filter"></label>
        </td>
      </tr>
      <tr>
        <td class="right" colspan="4">
            <input type="submit" class="submit small" value="Submit">
        </td>
      </tr>
    </tbody>
  </table>
</form>
{include file="global/block_footer.tpl"}
