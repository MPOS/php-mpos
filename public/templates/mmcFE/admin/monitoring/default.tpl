{foreach $CRONSTATUS as $k=>$v}
  {include file="global/block_header.tpl" BLOCK_HEADER="$k"}
    <table width="55%">
      <thead>
        <th>Event Name</th>
        <th>Status</th>
      </thead>
      <tbody>
        {foreach $v as $event}
        <tr>
          <td>{$event.NAME}</td>
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
            {else}
              {$event.STATUS.value|default:""}
            {/if}
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </form>
  {include file="global/block_footer.tpl"}
{/foreach}
