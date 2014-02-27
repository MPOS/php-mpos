  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations">Registrations</a>
      </div>
      <div class="panel-body">
        <table class="table">

          <thead>
            <tr>
              <th align="center">24 hours</th>
              <th align="center">7 days</th>
              <th align="center">1 month</th>
              <th align="center">6 months</th>
              <th align="center">1 year</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td align="center">{$USER_REGISTRATIONS.24hours}</td>
              <td align="center">{$USER_REGISTRATIONS.7days}</td>
              <td align="center">{$USER_REGISTRATIONS.1month}</td>
              <td align="center">{$USER_REGISTRATIONS.6month}</td>
              <td align="center">{$USER_REGISTRATIONS.1year}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>