{include file="global/block_header.tpl" BLOCK_HEADER="Monitoring"}
    <table width="88%">
      <thead>
        <th>Cronjob</th>
        <th>Exit Code</th>
        <th>Active</th>
        <th>Runtime</th>
        <th>Last Run</th>
        <th>Message</th>
      </thead>
      <tbody>
{foreach $CRONSTATUS as $cron=>$v}
        <tr>
          <td>{$cron}</td>
  {foreach $v as $event}
          <td>
            {if $event.STATUS.type == 'okerror'}
              {if $event.STATUS.value == 0}
                <font color="green">OK</font>
              {else}
                <font color="red">ERROR</font>
              {/if}
            {else if $event.STATUS.type == 'message'}
              <i>{$event.STATUS.value}</i>
            {else if $event.STATUS.type == 'yesno'}
              {if $event.STATUS.value == 1}
                Yes 
              {else}
                No  
              {/if}
            {else if $event.STATUS.type == 'time'}
              {$event.STATUS.value|default:"0"|number_format:"2"} seconds
            {else if $event.STATUS.type == 'date'}
              {$event.STATUS.value|date_format:"%m/%d %H:%M:%S"}
            {else}
              {$event.STATUS.value|default:""}
            {/if}
          </td>
  {/foreach}
        </tr>
{/foreach}
      </tbody>
    </table>
{include file="global/block_footer.tpl"}
