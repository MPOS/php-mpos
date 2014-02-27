<div class="row">
  <div class="col-lg-3">
    <div class="panel panel-info">
      <div class="panel-heading">
        Add New Worker
      </div>

      <form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
        <input type="hidden" name="page" value="{$smarty.request.page|escape}">
        <input type="hidden" name="action" value="{$smarty.request.action|escape}">
        <input type="hidden" name="do" value="add">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <div class="panel-body">
          <div class="form-group">
            <label>Worker Name</label>
            <input class="form-control" type="text" name="username" value="user" size="10" maxlength="20" required>
          </div>
          <div class="form-group">
            <label>Worker Password</label>
            <input class="form-control" type="text" name="password" value="password" size="10" maxlength="20" required>&nbsp;
          </div>
          <input type="submit" value="Add New Worker" class="btn btn-outline btn-success btn-lg btn-block">
        </div>
      </form>
    </div>
  </div>

  <div class="col-lg-9">
    <div class="panel panel-info">
      <div class="panel-heading">
        Worker Configuration
      </div>
      
      <form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
        <input type="hidden" name="page" value="{$smarty.request.page|escape}">
        <input type="hidden" name="action" value="{$smarty.request.action|escape}">
        <input type="hidden" name="do" value="update">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-hover">
             <thead>
                <tr>
                  <th align="left">Worker Login</th>
                  <th align="left">Worker Password</th>
                  <th align="center">Active</th>
                  {if $GLOBAL.config.disable_notifications != 1}<th align="center">Monitor</th>{/if}
                  <th align="right">Khash/s</th>
                  <th align="right">Difficulty</th>
                  <th align="center" style="padding-right: 25px;">Action</th>
                </tr>
             </thead>
             <tbody>
               {nocache}
               {section worker $WORKERS}
               {assign var="username" value="."|escape|explode:$WORKERS[worker].username:2} 
               <tr>
                 <td align="left"{if $WORKERS[worker].hashrate > 0} style="color: orange"{/if}>{$username.0|escape}.<input class="form-control" name="data[{$WORKERS[worker].id}][username]" value="{$username.1|escape}" size="10" required/></td>
                 <td align="left"><input class="form-control" type="text" name="data[{$WORKERS[worker].id}][password]" value="{$WORKERS[worker].password|escape}" size="10" required></td>
                 <td align="center"><i class="fa fa-{if $WORKERS[worker].hashrate > 0}check{else}times {/if}fa-fw"></i></td>
                 {if $GLOBAL.config.disable_notifications != 1}
                 <td align="center">
                   <label for="data[{$WORKERS[worker].id}][monitor]">
                     <input type="checkbox" name="data[{$WORKERS[worker].id}][monitor]" value="1" id="data[{$WORKERS[worker].id}][monitor]" {if $WORKERS[worker].monitor}checked{/if} />
                   </label>
                 </td>
                 {/if}
                 <td align="right">{$WORKERS[worker].hashrate|number_format}</td>
                 <td align="right">{$WORKERS[worker].difficulty|number_format:"2"}</td>
                 <td align="center" style="padding-right: 25px;"><a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=delete&id={$WORKERS[worker].id|escape}&ctoken={$CTOKEN}"><i class="fa fa-trash-o fa-fw"></i></a></td>
               </tr>
               {/section}
               {/nocache}
             </tbody>
          </table>
          <input type="submit" class="btn btn-outline btn-success btn-lg btn-block" value="Update Workers">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
