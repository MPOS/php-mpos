{if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-desktop fa-fw"></i> Worker Information</h4>
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-bordered table-hover table-striped"> 
         <thead>
          <tr>
            <th>Worker</th>
            <th>Hashrate</th>
            <th>Difficulty</th>
          </tr>
          </thead>
          <tbody id="b-workers">
            <td colspan="3" class="text-center">No worker information available</td>
          </tbody>
        </table>
      </div>
    </div>
  </div>
{/if}
