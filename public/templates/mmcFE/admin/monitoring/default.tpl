{include file="global/block_header.tpl" BLOCK_HEADER="Monitoring"}
    <table width="88%">
      <thead>
        <th>Cronjob</th>
        <th>Exit Code</th>
        <th>Active</th>
        <th>Runtime</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Message</th>
      </thead>
      <tbody>
{foreach $CRONSTATUS as $cron => $data}
        <tr>
          <td>{$cron}</td>
  {foreach $data as $name => $event}
          <td>
            {if $event.type == 'okerror'}
              {if $event.value == 0}
                <font color="green">OK</font>
              {else}
                <font color="red">ERROR</font>
              {/if}
            {else if $event.type == 'message'}
              <i>{$event.value}</i>
            {else if $event.type == 'yesno'}
              <img src="{$PATH}/images/{if $event.value == 1}success{else}error{/if}.gif" />
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
{include file="global/block_footer.tpl"}
