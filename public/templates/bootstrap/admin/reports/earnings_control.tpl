<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape|escape}">
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        Earnings Information
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <td>
                <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($REPORTDATA) && count($REPORTDATA) > ($BLOCKLIMIT - 1)}{$REPORTDATA[$BLOCKLIMIT - 1].height}{/if}&prev=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"<i class="icon-left-open"></i></a>
              </td>
              <td>
                <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($REPORTDATA) && count($REPORTDATA) > 0}{$REPORTDATA[0].height}{/if}&next=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"><i class="icon-right-open"></i></a>
              </td>
            </thead>
          </table>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <tbody>
              <tr>
                <td>
                  <div class="form-group">
                    <label>Select User</label>
                    {html_options class="form-control" name="id" options=$USERLIST selected=$USERID|default:"0"}
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <label>Block Limit</label>
                    <input size="10" class="form-control" type="text" name="limit" value="{$BLOCKLIMIT|default:"20"}" />
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <label>Starting block height</label>
                    <input type="text" class="form-control" name="search" value="{$HEIGHT|default:"%"}">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <label>SHOW EMPTY ROUNDS</label>
                    <input type="checkbox" class="form-control" name="filter" value="1" id="filter" {if $FILTER}checked{/if} />
                  </div>
                </td>
            </tbody>
          </table>
          <input type="submit" value="Submit" class="btn btn-outline btn-success btn-lg btn-block">
        </div>
      </div>
    </div>
  </div>
</div>
</form>
