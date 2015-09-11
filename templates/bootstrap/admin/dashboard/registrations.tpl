  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-pencil fa-fw"></i> <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations">{t}Registrations{/t}</a>
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">

          <thead>
            <tr>
              <th>{t 1=24}%1 hours{/t}</th>
              <th>{t 1=7}%1 days{/t}</th>
              <th>{t}1 month{/t}</th>
              <th>{t 1=6}%1 months{/t}</th>
              <th>{t}1 year{/t}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{$USER_REGISTRATIONS.24hours}</td>
              <td>{$USER_REGISTRATIONS.7days}</td>
              <td>{$USER_REGISTRATIONS.1month}</td>
              <td>{$USER_REGISTRATIONS.6month}</td>
              <td>{$USER_REGISTRATIONS.1year}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>