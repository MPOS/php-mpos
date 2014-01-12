{nocache}
<article class="module width_quarter">
  <header><h3>MPOS Status</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th colspan="2" align="center">Cronjobs</th>
        <th align="center">Wallet</th>
      </tr>
      <tr>
        <th align="center"><strong>Errors</strong></th>
        <th align="center"><strong>Disabled</strong></th>
        <th align="center"><strong>Errors</strong></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="center">
          <a href="{$smarty.server.PHP_SELF}?page=admin&action=monitoring">{if $CRON_ERROR == 0}None - OK{else}{$CRON_ERROR}{/if}</a>
        </td>
        <td align="center">
          <a href="{$smarty.server.PHP_SELF}?page=admin&action=monitoring">{if $CRON_DISABLED == 0}None - OK{else}{$CRON_DISABLED}{/if}</a>
        </td>
        <td align="center">
          <a href="{$smarty.server.PHP_SELF}?page=admin&action=wallet">{$WALLET_ERROR|default:"None - OK"}</a>
        </td>
      </tr>
    </tbody>
  </table>
</article>
{/nocache}
