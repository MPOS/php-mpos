<article class="module width_full"> 
  <header><h3>Monitoring</h3></header>
    <table class="tablesorter" cellspacing="0">
      <thead>
        <th>Cronjob</th>
        <th align="center">Disabled</th>
        <th align="center">Exit Code</th>
        <th align="center">Active</th>
        <th align="center">Runtime</th>
        <th align="center">Start Time</th>
        <th align="center">End Time</th>
        <th align="center">Message</th>
      </thead>
      <tbody>
{foreach $CRONSTATUS as $cron => $data}
        <tr>
          <td>{$cron}</td>
  {foreach $data as $name => $event}
          <td align="center">
            {if $event.type == 'okerror'}
              {if $event.value == 0}
                <span class="green">OK</font>
              {else}
                <span class="red">ERROR</font>
              {/if}
            {else if $event.type == 'message'}
              <i>{$event.value}</i>
            {else if $event.type == 'yesno'}
              <i class="icon-{if $event.value == 1}ok{else}cancel{/if}"></i>
            {else if $event.type == 'time'}
              {if $event.value > 60}
                <span class="orange">
              {else if $event.value > 120}
                <span class="red">
              {else}
                <span class="green">
              {/if}
                {$event.value|default:"0"|number_format:"2"} seconds
              </span>
            {else if $event.type == 'date'}
              {if ($smarty.now - 180) > $event.value}
                <span class="red">
              {else if ($smarty.now - 120) > $event.value}
                <span class="orange">
              {else}
                <span class="green">
              {/if}
                {$event.value|date_format:"%m/%d %H:%M:%S"}
              </span>
            {else}
              {$event.value|default:""}
            {/if}
          </td>
  {/foreach}
        </tr>
{/foreach}
      </tbody>
    </table>
</article>
