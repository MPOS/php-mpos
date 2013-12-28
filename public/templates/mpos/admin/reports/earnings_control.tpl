<form action="{$smarty.server.PHP_SELF}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape|escape}">
<article class="module width_full">
  <header><h3>Earnings Information</h3></header>
<table class="tablesorter">
    <tbody>
        <td align="left">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($REPORTDATA) && count($REPORTDATA) > ($BLOCKLIMIT - 1)}{$REPORTDATA[$BLOCKLIMIT - 1].height}{/if}&prev=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"<i class="icon-left-open"></i></a>
        </td>
        <td align="right">
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($REPORTDATA) && count($REPORTDATA) > 0}{$REPORTDATA[0].height}{/if}&next=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"><i class="icon-right-open"></i></a>
        </td>
      </tr>
    </tbody>
  </table>
<table class="tablesorter">
    <tbody>
      <tr>
        <td>
          <fieldset style="width:200px; padding-right:8px;">
            <label>Select User</label>
            {html_options name="id" options=$USERLIST selected=$USERID|default:"0"}
          </fieldset>
        </td>
        <td>
          <fieldset style="width:200px; padding-right:8px;">
            <label>Block Limit</label>
            <input size="10" type="text" name="limit" value="{$BLOCKLIMIT|default:"20"}" />
          </fieldset>
        </td>
        <td>
          <fieldset style="width:200px; padding-right:8px;">
            <label>Starting block height</label>
            <input type="text" class="pin" name="search" value="{$HEIGHT|default:"%"}">
          </fieldset>
        </td>
        <td><b>SHOW EMPTY ROUNDS</b><br><br>
          <span style="margin: 0px 28px;" class="toggle">
            <label for="filter">
            <input type="checkbox" class="ios-switch" name="filter" value="1" id="filter" {if $FILTER}checked{/if} />
            <div class="switch"></div>
            </label>
          </span>
        </td>
    </tbody>
  </table>
  <footer>
    <div class="submit_link">
      <input type="submit" value="Submit" class="alt_btn">
    </div>
  </footer>
</article>
</form>
