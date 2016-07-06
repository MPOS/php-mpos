<div class="row">
  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Pool Donors
        </div>
        <span class="tools">
          <i class="fa fa-bitbucket"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover {if $DONORS}datatable{/if}">
            <thead>
              <tr>
                <th>Name</th>
                <th>%</th>
                <th>{$GLOBAL.config.currency} Total</th>
              </tr>
            </thead>
            <tbody>
              {section name=donor loop=$DONORS}
              <tr>
                <td>{if $DONORS[donor].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$DONORS[donor].username}{/if}</td>
                <td>{$DONORS[donor].donate_percent}</td>
                <td>{$DONORS[donor].donation|number_format:"2"}</td>
              </tr>
              {sectionelse}
              <tr>
                <td colspan="3">No confirmed donations yet, please be patient!</td>
              </tr>
              {/section}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
