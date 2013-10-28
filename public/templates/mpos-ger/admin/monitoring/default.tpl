<article class="module width_full"> 
  <header><h3>Monitoring</h3></header>
    <table class="tablesorter" cellspacing="0">
      <thead>
        <th>Cronjob</th>
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
                <font color="green">OK</font>
              {else}
                <font color="red">ERROR</font>
              {/if}
            {else if $event.type == 'message'}
              <i>{$event.value}</i>
            {else if $event.type == 'yesno'}
              <i class="icon-{if $event.value == 1}ok{else}cancel{/if}"></i>
            {else if $event.type == 'time'}
              {if $event.value > 60}
                <font color="orange">
              {else if $event.value > 120}
                <font color="red">
              {else}
                <font color="green">
              {/if}
                {$event.value|default:"0"|number_format:"2"} seconds
              </font>
            {else if $event.type == 'date'}
              {if ($smarty.now - 180) > $event.value}
                <font color="red">
              {else if ($smarty.now - 120) > $event.value}
                <font color="orange">
              {else}
                <font color="green">
              {/if}
                {$event.value|date_format:"%m/%d %H:%M:%S"}
              </font>
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
