  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-pencil fa-fw"></i> <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations">Registrations</a>
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">

          <thead>
            <tr>
              <th>24 hours</th>
              <th>7 days</th>
              <th>1 month</th>
              <th>6 months</th>
              <th>1 year</th>
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