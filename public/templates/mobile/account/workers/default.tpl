    <table width="100%">
      <tbody>
      <tr>
        <th align="left">Name</th>
        <th align="center">Active</th>
        <th align="right">Khash/s</th>
        <th align="right">Difficulty</th>
      </tr>
      {section worker $WORKERS}
      {assign var="username" value="."|escape|explode:$WORKERS[worker].username:2} 
      <tr>
        <td colspan="4" align="left"{if $WORKERS[worker].hashrate > 0} style="color: orange"{/if}>{$username.0|escape}.{$username.1|escape}</td>
      </tr>
      <tr>
        <td></td>
        <td align="center"><img src="{$PATH}/images/{if $WORKERS[worker].hashrate > 0}success{else}error{/if}.gif" /></td>
        <td align="right">{$WORKERS[worker].hashrate|number_format}</td>
        <td align="right">{$WORKERS[worker].difficulty|number_format:"2"}</td>
      </tr>
      {/section}
      </tbody>
    </table>
