<div class="row">
  <div class="col-lg-6">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Users
        </div>
        <span class="tools">
          <i class="fa fa-user"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Total</th>
                <th>Active</th>
                <th>Locked</th>
                <th>Admins</th>
                <th>No Fees</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{$USER_INFO.total}</td>
                <td>{$USER_INFO.active}</td>
                <td>{$USER_INFO.locked}</td>
                <td>{$USER_INFO.admins}</td>
                <td>{$USER_INFO.nofees}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
        
  <div class="col-lg-6">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Logins
        </div>
        <span class="tools">
          <i class="fa fa-sign-in"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
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
                <td>{$USER_LOGINS.24hours}</td>
                <td>{$USER_LOGINS.7days}</td>
                <td>{$USER_LOGINS.1month}</td>
                <td>{$USER_LOGINS.6month}</td>
                <td>{$USER_LOGINS.1year}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>