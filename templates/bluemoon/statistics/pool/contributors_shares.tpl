  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-tachometer fa-fw"></i> Contributor Shares
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Donor</th>
              <th>User Name</th>
              <th class="text-right">Shares</th>
            </tr>
          </thead>
          <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section shares $CONTRIBSHARES}
            {if $GLOBAL.userdata.username|default:""|lower == $CONTRIBSHARES[shares].account|lower}{assign var=listed value=1}<tr class="success">{else}<tr>{/if}
              <td>{$rank++}</td>
              <td>{if $CONTRIBSHARES[shares].donate_percent|default:"0" >= 2}<i class="fa fa-trophy fa-fw"></i>{else if $CONTRIBSHARES[shares].donate_percent|default:"0" < 2 AND $CONTRIBSHARES[shares].donate_percent|default:"0" > 0}<i class="fa fa-star-o fa-fw"></i>{else}<i class="fa fa-ban fa-fw"></i>{/if}</td>
              <td>{if $CONTRIBSHARES[shares].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$CONTRIBSHARES[shares].account|escape}{/if}</td>
              <td class="text-right">{$CONTRIBSHARES[shares].shares|number_format}</td>
            </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:"" && $GLOBAL.userdata.shares.valid|default:"0" > 0}
            {if $GLOBAL.userdata.username|default:""|lower == $CONTRIBHASHES[contrib].account|lower}{assign var=listed value=1}<tr class="success">{else}<tr>{/if}
              <td>n/a</td>
              <td>{if $GLOBAL.userdata.donate_percent|default:"0" >= 2}<i class="fa fa-trophy fa-fw"></i>{elseif $GLOBAL.userdata.donate_percent|default:"0" < 2 AND $GLOBAL.userdata.donate_percent|default:"0" > 0}<i class="fa fa-star-o fa-fw"></i>{else}<i class="fa fa-ban fa-fw"></i>{/if}</td>
              <td>{$GLOBAL.userdata.username|escape}</td>
              <td class="text-right">{$GLOBAL.userdata.shares.valid|number_format}</td>
            </tr>
{/if}
          </tbody>
        </table>
      </div>
      <div class="panel-footer">
          <h6>
          <i class="fa fa-ban fa-fw"></i>no Donation
          <i class="fa fa-star-o fa-fw"></i> 0&#37;&#45;2&#37; Donation 
          <i class="fa fa-trophy fa-fw"></i> 2&#37; or more Donation
          </h6>
      </div>
    </div>
  </div>
