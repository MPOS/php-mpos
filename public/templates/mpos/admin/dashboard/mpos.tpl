{nocache}
<article class="module width_quarter">
  <header><h3>MPOS Version Information</h3></header>
  <table width="25%" class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th>Component</th>
        <th align="center">Current</th>
        <th align="center">Installed</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>MPOS</strong></td>
        <td align="center"><font color="green">{$VERSION['CURRENT']['CORE']}</font></td>
        <td align="center">
          <font color="{if $VERSION['INSTALLED']['CORE'] == $VERSION['CURRENT']['CORE']}green{else}red{/if}">{$VERSION['INSTALLED']['CORE']}</font>
        </td>
      </tr>
      <tr>
        <td><strong>Config</strong></td>
        <td align="center"><font color="green">{$VERSION['CURRENT']['CONFIG']}</font></td>
        <td align="center">
          <font color="{if $VERSION['INSTALLED']['CONFIG'] == $VERSION['CURRENT']['CONFIG']}green{else}red{/if}">{$VERSION['INSTALLED']['CONFIG']}</font>
        </td>
      </tr>
      <tr>
        <td><strong>Database</strong></td>
        <td align="center"><font color="green">{$VERSION['CURRENT']['DB']}</font></td>
        <td align="center">
          <font color="{if $VERSION['INSTALLED']['DB'] == $VERSION['CURRENT']['DB']}green{else}red{/if}">{$VERSION['INSTALLED']['DB']}</font>
        </td>
      </tr>
    </tbody>
  </table>
</article>

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
          <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">{if $CRON_ERROR == 0}None - OK{else}{$CRON_ERROR}{/if}</a>
        </td>
        <td align="center">
          <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">{if $CRON_DISABLED == 0}None - OK{else}{$CRON_DISABLED}{/if}</a>
        </td>
        <td align="center">
          <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet">{$WALLET_ERROR|default:"None - OK"}</a>
        </td>
      </tr>
    </tbody>
  </table>
</article>
{/nocache}




