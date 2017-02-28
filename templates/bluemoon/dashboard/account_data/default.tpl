      <div class="wrapper">
        <div class="widget no-margin">
          <div class="widget-header">
            <div class="title">{$GLOBAL.config.currency} Account Balance</div>
          </div>
        </div>
        <div class="list-group no-margin">
          <a title="" data-original-title="" class="list-group-item">
            <span class="pull-right">
              <i class="fa fa-check-square-o fa-3x text-success"></i>
            </span>
            <h4 class="list-group-item-heading"><span id="b-confirmed">{$GLOBAL.userdata.balance.confirmed|number_format:"6"}</span></h4>
            <p class="list-group-item-text">Confirmed</p>
          </a>
          <a title="" data-original-title="" class="list-group-item">
            <span class="pull-right">
              <i class="fa fa-square-o fa-3x text-warning"></i>
            </span>
            <h4 class="list-group-item-heading"><span id="b-unconfirmed">{$GLOBAL.userdata.balance.unconfirmed|number_format:"6"}</span></h4>
            <p class="list-group-item-text">Unconfirmed</p>
          </a>
        </div>
      </div>