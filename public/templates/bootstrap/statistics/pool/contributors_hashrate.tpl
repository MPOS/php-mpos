  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-tachometer fa-fw"></i> Contributor Hashrates
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Donor</th>
              <th>User Name</th>
              <th class="text-right">KH/s</th>
              <th class="text-right">{$GLOBAL.config.currency}/Day</th>
              {if $GLOBAL.config.price.enabled}
              {if $GLOBAL.config.price.currency}<th class="text-right">{$GLOBAL.config.price.currency}/Day</th>{/if}
               {/if}
            </tr>
          </thead>
          <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $CONTRIBHASHES}
      {math assign="estday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$CONTRIBHASHES[contrib].hashrate}
            {if $GLOBAL.userdata.username|default:""|lower == $CONTRIBHASHES[contrib].account|lower}{assign var=listed value=1}<tr class="success">{else}<tr>{/if}
              <td>{$rank++}</td>
              <td>{if $CONTRIBHASHES[contrib].donate_percent|default:"0" >= 2}<i class="fa fa-trophy fa-fw"></i>{elseif $CONTRIBHASHES[contrib].donate_percent|default:"0" < 2 AND $CONTRIBHASHES[contrib].donate_percent|default:"0" > 0}<i class="fa fa-star-o fa-fw"></i>{else}<i class="fa fa-ban fa-fw"></i>{/if}</td>
              <td>{if $CONTRIBHASHES[contrib].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$CONTRIBHASHES[contrib].account|escape}{/if}</td>
              <td class="text-right">{$CONTRIBHASHES[contrib].hashrate|number_format}</td>
              <td class="text-right">{$estday|number_format:"3"}</td>
              {if $GLOBAL.config.price.enabled}
              {if $GLOBAL.config.price.currency}<td class="text-right">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"4"}</td>{/if}
              {/if}
            </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:"" && $GLOBAL.userdata.rawhashrate|default:"0" > 0}
      {math assign="myestday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$GLOBAL.userdata.rawhashrate}
            {if $GLOBAL.userdata.username|default:""|lower == $CONTRIBHASHES[contrib].account|lower}{assign var=listed value=1}<tr class="success">{else}<tr>{/if}
              <td>n/a</td>
              <td>{if $GLOBAL.userdata.donate_percent|default:"0" >= 2}<i class="fa fa-trophy fa-fw"></i>{elseif $GLOBAL.userdata.donate_percent|default:"0" < 2 AND $GLOBAL.userdata.donate_percent|default:"0" > 0}<i class="fa fa-star-o fa-fw"></i>{else}<i class="fa fa-ban fa-fw"></i>{/if}</td>
              <td>{$GLOBAL.userdata.username|escape}</td>
              <td class="text-right">{$GLOBAL.userdata.rawhashrate|number_format}</td>
              <td class="text-right">{$myestday|number_format:"3"|default:"n/a"}</td>
              {if $GLOBAL.config.price.enabled}
              {if $GLOBAL.config.price.currency}<td class="text-right">{($myestday * $GLOBAL.price)|default:"n/a"|number_format:"4"}</td>{/if}
              {/if}
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

