<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-bell-o fa-fw"></i> {t}Monitoring{/t}
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <th>{t}Cronjob{/t}</th>
            <th>{t}Disabled{/t}</th>
            <th>{t}Exit Code{/t}</th>
            <th>{t}Active{/t}</th>
            <th>{t}Runtime{/t}</th>
            <th>{t}Start Time{/t}</th>
            <th>{t}End Time{/t}</th>
            <th>{t}Message{/t}</th>
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
                    <font color="red">{t}ERROR{/t}</font>
                  {/if}
                {else if $event.type == 'message'}
                  <i>{$event.value}</i>
                {else if $event.type == 'yesno'}
                  <i class="fa fa-{if $event.value == 1}check{else}times{/if} fa-fw"></i>
                {else if $event.type == 'time'}
                  {if $event.value > 60}
                    <font color="orange">
                  {else if $event.value > 120}
                    <font color="red">
                  {else}
                    <font color="green">
                  {/if}
                    {$event.value|default:"0"|number_format:"2"} {t}seconds{/t}
                  </font>
                {else if $event.type == 'date'}
                  {if ($smarty.now - 180) > $event.value}
                    <font color="red">
                  {else if ($smarty.now - 120) > $event.value}
                    <font color="orange">
                  {else}
                    <font color="green">
                  {/if}
                    {$event.value|date_format:$GLOBAL.config.date}
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
      </div>
    </div>
  </div>
</div>
