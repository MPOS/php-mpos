{include file="global/block_header.tpl" BLOCK_HEADER="{count($WORKERS)} Current Active Pool Workers"}
<center>
  <table border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th>Worker Name</th>
        <th>Password</th>
        <th class="center">Active</th>
        {if $GLOBAL.config.disable_notifications != 1}<th class="center">Monitor</th>{/if}
        <th class="right">Khash/s</th>
        <th class="right">Difficulty</th>
        <th class="right">Avg Difficulty</th>
      </tr>
    </thead>
      {nocache}
      {section worker $WORKERS}
    <tbody>
      <tr>
        <td>{$WORKERS[worker].username|escape}</td>
        <td>{$WORKERS[worker].password|escape}</td>
        <td class="center"><img src="{$PATH}/images/{if $WORKERS[worker].hashrate > 0}success{else}error{/if}.gif" /></td>
        {if $GLOBAL.config.disable_notifications != 1}
        <td class="center">
          <img src="{$PATH}/images/{if $WORKERS[worker].monitor}success{else}error{/if}.gif" />
        </td>
        {/if}
        <td class="right">{$WORKERS[worker].hashrate|number_format|default:"0"}</td>
        <td class="right">{if $WORKERS[worker].hashrate > 0}{$WORKERS[worker].difficulty|number_format:"2"|default:"0"}{else}0{/if}</td>
        <td class="right">{if $WORKERS[worker].hashrate > 0}{$WORKERS[worker].avg_difficulty|number_format:"2"|default:"0"}{else}0{/if}</td>
      </tr>
      {/section}
      {/nocache}
      </tbody>
    </table>
</center>
{include file="global/block_footer.tpl"}
