<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-desktop fa-fw"></i> {$GLOBAL.workers} Current Active Pool Workers
      </div>
      <div class="panel-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Worker Name</th>
                <th>Password</th>
                <th>Active</th>
                {if $GLOBAL.config.disable_notifications != 1 && $DISABLE_IDLEWORKERNOTIFICATIONS != 1}<th>Monitor</th>{/if}
                <th>Khash/s</th>
                <th>Difficulty</th>
                <th style="padding-right: 25px;">Avg Difficulty</th>
              </tr>
            </thead>
            {nocache}
            {section worker $WORKERS}
            <tbody>
              <tr>
                <td>{$WORKERS[worker].username|escape}</td>
                <td>{$WORKERS[worker].password|escape}</td>
                <td align="center"><i class="fa fa-{if $WORKERS[worker].hashrate > 0}check{else}times{/if} fa-fw"></i></td>
                {if $GLOBAL.config.disable_notifications != 1 && $DISABLE_IDLEWORKERNOTIFICATIONS != 1}
                <td align="center"><i class="fa fa-{if $WORKERS[worker].monitor}check{else}times{/if} fa-fw"></i></td>
                {/if}
                <td>{$WORKERS[worker].hashrate|number_format|default:"0"}</td>
                <td>{if $WORKERS[worker].hashrate > 0}{$WORKERS[worker].difficulty|number_format:"2"|default:"0"}{else}0{/if}</td>
                <td style="padding-right: 25px;">{if $WORKERS[worker].hashrate > 0}{$WORKERS[worker].avg_difficulty|number_format:"2"|default:"0"}{else}0{/if}</td>
              </tr>
              {/section}
              {/nocache}
            </tbody>
          </table>
        </div>
        <div class="panel-body">
          <form action="{$smarty.server.SCRIPT_NAME}">
            <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
            <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
            <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
            <ul class="pager">
              <li class="previous {if $smarty.get.start|default:"0" <= 0}disabled{/if}">
                <a href="{if $smarty.get.start|default:"0" <= 0}#{else}{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}{/if}"> &larr;</a>
              </li>
              <li class="next">
                <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"> &rarr;</a>
              </li>
            </ul>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> 
