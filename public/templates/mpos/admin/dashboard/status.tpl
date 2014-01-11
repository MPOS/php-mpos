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
{/nocache}
