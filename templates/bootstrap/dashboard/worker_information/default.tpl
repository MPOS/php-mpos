{if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-desktop fa-fw"></i> {t}Worker Information{/t}</h4>
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-bordered table-hover table-striped"> 
         <thead>
          <tr>
            <th>{t}Worker{/t}</th>
            <th>{t}Hashrate{/t}</th>
            <th>{t}Difficulty{/t}</th>
          </tr>
          </thead>
          <tbody id="b-workers">
            <td colspan="3" class="text-center">{t}No worker information available{/t}</td>
          </tbody>
        </table>
      </div>
    </div>
  </div>
{/if}
