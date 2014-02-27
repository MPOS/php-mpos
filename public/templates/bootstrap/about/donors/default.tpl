<fiv class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        Pool Donors
      </div>
      <div class="panel-body">
      <table class="table">
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
            <td align="center" colspan="3">No confirmed donations yet, please be patient!</td>
          </tr>
{/section}
        </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
