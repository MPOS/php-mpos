{if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  <div class="widget">
    <div class="widget-header">
      <div class="title">
        Worker Information
      </div>
      <span class="tools">
        <i class="fa fa-desktop"></i>
      </span>
    </div>
    <div class="widget-body">
      <div class="table-responsive">
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
