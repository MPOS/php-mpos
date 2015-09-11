<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-user fa-fw"></i> {t}Users{/t}
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>{t}Total{/t}</th>
              <th>{t}Active{/t}</th>
              <th>{t}Locked{/t}</th>
              <th>{t}Admins{/t}</th>
              <th>{t}No Fees{/t}</th>
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
        
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-sign-in fa-fw"></i> {t}Logins{/t}
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