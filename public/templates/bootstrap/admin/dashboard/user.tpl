<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Users
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th align="center">Total</th>
              <th align="center">Active</th>
              <th align="center">Locked</th>
              <th align="center">Admins</th>
              <th align="center">No Fees</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td align="center">{$USER_INFO.total}</td>
              <td align="center">{$USER_INFO.active}</td>
              <td align="center">{$USER_INFO.locked}</td>
              <td align="center">{$USER_INFO.admins}</td>
              <td align="center">{$USER_INFO.nofees}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
        
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Logins
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
              <td align="center">{$USER_LOGINS.24hours}</td>
              <td align="center">{$USER_LOGINS.7days}</td>
              <td align="center">{$USER_LOGINS.1month}</td>
              <td align="center">{$USER_LOGINS.6month}</td>
              <td align="center">{$USER_LOGINS.1year}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>