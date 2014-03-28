<form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
  <input type="hidden" name="page" value="{$smarty.request.page|escape|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape|escape}">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
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
                <tr>
                  <td colspan="4">
                    <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($REPORTDATA) && count($REPORTDATA) > ($BLOCKLIMIT - 1)}{$REPORTDATA[$BLOCKLIMIT - 1].height}{/if}&prev=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"<i class="fa fa-chevron-left fa-fw"></i></a>
                    <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($REPORTDATA) && count($REPORTDATA) > 0}{$REPORTDATA[0].height}{/if}&next=1&limit={$BLOCKLIMIT}&id={$USERID}&filter={$FILTER}"><i class="fa fa-chevron-right fa-fw pull-right"></i></a>
                  </td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="form-group">
                      <label>Select User</label>
                      {html_options class="form-control select-mini" name="id" options=$USERLIST selected=$USERID|default:"0"}
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
                      <label>Show empty rounds</label>
                      <br>
                      <input type="hidden" name="filter" value="0" />
                      <input type="checkbox" data-size="small"  name="filter" id="filter" value="1" {if $FILTER}checked{/if}/>
                      <script>
                      $("[id='filter']").bootstrapSwitch();
                      </script>
                    </div>
                  </td>
                </tbody>
              </table>
            </div>
          </div>
          <div class="panel-footer">
            <input type="submit" value="Submit" class="btn btn-success btn-sm">
          </div>
        </div>
      </div>
    </div>
  </form>
