    <table>
      <tbody>
      <tr>
        <th align="left">Worker Name</th>
        <th align="center">Active</th>
        <th align="right">Khash/s</th>
        <th align="right">Difficulty</th>
      </tr>
      {section worker $WORKERS}
      {assign var="username" value="."|escape|explode:$WORKERS[worker].username:2} 
      <tr>
        <td align="left"{if $WORKERS[worker].active} style="color: orange"{/if}>{$username.0|escape}.{$username.1|escape}</td>
        <td align="center"><img src="{$PATH}/images/{if $WORKERS[worker].hashrate > 0}success{else}error{/if}.gif" /></td>
        <td align="right">{$WORKERS[worker].hashrate|number_format}</td>
        <td class="right">{$WORKERS[worker].difficulty|number_format:"2"}</td>
      </tr>
      {/section}
      </tbody>
    </table>
