<div class="row">
  <form class="col-lg-3" method="POST" role="form">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="add">
    <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-plus-square-o fa-fw"></i> Add New Worker
      </div>
        <div class="panel-body">
          <div class="form-group">
            <label>Worker Name</label>
            <input class="form-control" type="text" name="username" value="user" size="10" maxlength="20" required>
          </div>
          <div class="form-group">
            <label>Worker Password</label>
            <input class="form-control" type="text" name="password" value="password" size="10" maxlength="20" required>
          </div>
        </div>
      <div class="panel-footer">
        <input type="submit" value="Add New Worker" class="btn btn-success btn-sm">
      </div>
    </div>
  </form>

  <div class="col-lg-9">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-gears fa-fw"></i> Worker Configuration
      </div>
      <form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
        <input type="hidden" name="page" value="{$smarty.request.page|escape}">
        <input type="hidden" name="action" value="{$smarty.request.action|escape}">
        <input type="hidden" name="do" value="update">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <div class="panel-body no-padding">
          <div class="table-responsive">
          <table class="table">
             <thead>
                <tr>
                  <th class="smallwidth">Worker Login</th>
                  <th class="smallwidth">Worker Password</th>
                  <th class="text-center">Active</th>
                  {if $GLOBAL.config.disable_notifications != 1 && $DISABLE_IDLEWORKERNOTIFICATIONS != 1}<th class="text-center">Monitor</th>{/if}
                  <th class="text-right">Khash/s</th>
                  <th class="text-right">Difficulty</th>
                  <th class="text-center">Action</th>
                </tr>
             </thead>
             <tbody>
               {nocache}
               {section worker $WORKERS}
               {assign var="username" value="."|escape|explode:$WORKERS[worker].username:2} 
               <tr>
                 <td>
                  <div class="input-group input-group-sm">
                    <span {if $WORKERS[worker].hashrate > 0}style="color: orange"{/if} class="input-group-addon">{$username.0|escape}.</span>
                    <input type="text" name="data[{$WORKERS[worker].id}][username]" value="{$username.1|escape}" size="10" required class="form-control" />
                  </div>
                 </td>
                 <td><input class="form-control" type="text" name="data[{$WORKERS[worker].id}][password]" value="{$WORKERS[worker].password|escape}" size="10" required></td>
                 <td class="text-center"><i class="fa fa-{if $WORKERS[worker].hashrate > 0}check{else}times{/if} fa-fw"></i></td>
                 {if $GLOBAL.config.disable_notifications != 1 && $DISABLE_IDLEWORKERNOTIFICATIONS != 1}
                 <td class="text-center">
                   <input type="hidden" name="data[{$WORKERS[worker].id}][monitor]" value="0" />
                   <input type="checkbox" class="switch" data-size="mini"  name="data[{$WORKERS[worker].id}][monitor]" id="data[{$WORKERS[worker].id}][monitor]" value="1" {if $WORKERS[worker].monitor}checked{/if}/>
                 </td>
                 {/if}
                 <td class="text-right">{$WORKERS[worker].hashrate|number_format}</td>
                 <td class="text-right">{$WORKERS[worker].difficulty|number_format:"2"}</td>
                 <td class="text-center"><a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=delete&id={$WORKERS[worker].id|escape}&ctoken={$CTOKEN|escape|default:""}"><i class="fa fa-trash-o fa-fw"></i></a></td>
               </tr>
               {/section}
               {/nocache}
             </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <input type="submit" class="btn btn-success btn-sm" value="Update Workers">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
