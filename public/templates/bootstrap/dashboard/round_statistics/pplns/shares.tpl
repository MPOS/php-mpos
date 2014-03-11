      <table class="table borderless m-b-none text-small">
        <thead>
          <tr>
            <th></th>
            <th><span class="pull-right"><h5><i class="fa fa-user fa-fw"></i> Own<h5></th>
            <th><span class="pull-right"><h5><i class="fa fa-users fa-fw"></i> Pool</h6></span></th>
          </tr>
          <tr>
            <th><h5><i class="fa fa-check fa-fw"></i> Valid</h6></th>
            <th><span class="pull-right"><h5 id="b-yvalid">{$GLOBAL.userdata.shares.valid|number_format}</h5></span></th>
            <th><span class="pull-right"><h5 id="b-pvalid">{$GLOBAL.roundshares.valid|number_format}</h5></span></th>
            
          </tr>
          <tr>
            <th><h5><i class="fa fa-times fa-fw"></i> Invalid</h6></th>
            <th><span class="pull-right"><h5 id="b-yivalid">{$GLOBAL.userdata.shares.invalid|number_format}</h5></span></th>
            <th><span class="pull-right"><h5 id="b-pivalid">{$GLOBAL.roundshares.invalid|number_format}</h5></span></th>
          </tr>
          <tr>
            <th><h5><i class="fa fa-bolt fa-fw"></i> Efficiency</h5></th>
            <th><span class="pull-right"><h5 id="b-yefficiency">{if $GLOBAL.userdata.shares.valid > 0}{(100 - ($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}</h5></span></th>
            <th><span class="pull-right"><h5 id="b-pefficiency">{if $GLOBAL.roundshares.valid > 0}{(100 - ($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}</h5></span></th>
          </tr>
        </thead>
      </table>
