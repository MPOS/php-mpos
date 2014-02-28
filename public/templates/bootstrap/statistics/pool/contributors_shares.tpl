  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Contributor Shares
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Donor</th>
              <th>User Name</th>
              <th style="padding-right: 30px;">Shares</th>
            </tr>
          </thead>
          <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section shares $CONTRIBSHARES}
            <tr{if $GLOBAL.userdata.username|default:""|lower == $CONTRIBSHARES[shares].account|lower}{assign var=listed value=1} style="background-color:#99EB99;"{else}{/if}>
              <td>{$rank++}</td>
              <td>{if $CONTRIBSHARES[shares].donate_percent|default:"0" >= 2}<i class="fa fa-trophy fa-fw">{else if $CONTRIBSHARES[shares].donate_percent|default:"0" < 2 AND $CONTRIBSHARES[shares].donate_percent|default:"0" > 0}<i class="fa fa-star-o fa-fw">{else}<i class="fa fa-ban fa-fw"></i>{/if}</td>
              <td>{if $CONTRIBSHARES[shares].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$CONTRIBSHARES[shares].account|escape}{/if}</td>
              <td style="padding-right: 30px;">{$CONTRIBSHARES[shares].shares|number_format}</td>
            </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:"" && $GLOBAL.userdata.shares.valid|default:"0" > 0}
            <tr>
              <td>n/a</td>
              <td>{if $GLOBAL.userdata.donate_percent|default:"0" >= 2}<i class="fa fa-trophy fa-fw"></i>{elseif $GLOBAL.userdata.donate_percent|default:"0" < 2 AND $GLOBAL.userdata.donate_percent|default:"0" > 0}<i class="fa fa-star-o fa-fw"></i>{else}<i class="fa fa-ban fa-fw"></i>{/if}</td>
              <td>{$GLOBAL.userdata.username|escape}</td>
              <td style="padding-right: 30px;">{$GLOBAL.userdata.shares.valid|number_format}</td>
            </tr>
{/if}
          </tbody>
        </table>
      </div>
      <div class="panel-footer">
        <ul>
          <i class="fa fa-ban fa-fw"></i>no Donation
          <i class="fa fa-star-o fa-fw"></i> 0&#37;&#45;2&#37; Donation 
          <i class="fa fa-trophy fa-fw"></i> 2&#37; or more Donation 
        </ul>
      </div>
      <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
  </div>
