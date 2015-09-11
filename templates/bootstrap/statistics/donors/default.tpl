<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-bitbucket fa-fw"></i> {t}Pool Donors{/t}
      </div>
      <div class="panel-body table-responsive">
      <table class="table table-striped table-bordered table-hover {if $DONORS}datatable{/if}">
        <thead>
          <tr>
            <th>{t}Name{/t}</th>
            <th>%</th>
            <th>{$GLOBAL.config.currency} {t}Total{/t}</th>
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
            <td colspan="3">{t}No confirmed donations yet, please be patient!{/t}</td>
          </tr>
{/section}
        </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
