      <table class="table borderless m-b-none text-small">
        <thead>
          <tr>
            <th></th>
            <th><span><h5><i class="fa fa-user fa-fw"></i> Own<h5></th>
            <th><span><h5><i class="fa fa-users fa-fw"></i> Pool</h6></span></th>
          </tr>
          <tr>
            <th><h5><i class="fa fa-smile-o fa-fw"></i> Valid</h6></th>
            <th>
            <div class="progress progress-striped progress-fix">
               <div id="b-yvalid" class="progress-bar progress-bar-success black" style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{$GLOBAL.userdata.shares.valid|number_format}" role="progressbar">{$GLOBAL.userdata.shares.valid|number_format}</div>
            </div>
            </th>
            <th>
            <div class="progress progress-striped progress-fix">
               <div id="b-pvalid" class="progress-bar progress-bar-success black" style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{$GLOBAL.roundshares.valid|number_format}" role="progressbar">{$GLOBAL.roundshares.valid|number_format}</div>
            </div>
            </th>
          </tr>
          <tr>
            <th><h5><i class="fa fa-frown-o fa-fw"></i> Invalid</h6></th>
            <th>
            <div class="progress progress-striped progress-fix">
               <div id="b-yivalid" class="progress-bar progress-bar-danger black" style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{$GLOBAL.userdata.shares.invalid|number_format}" role="progressbar">{$GLOBAL.userdata.shares.invalid|number_format}</div>
            </div>
            </th>
            <th>
            <div class="progress progress-striped progress-fix">
               <div id="b-pivalid" class="progress-bar progress-bar-danger black" style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{$GLOBAL.roundshares.invalid|number_format}" role="progressbar">{$GLOBAL.roundshares.invalid|number_format}</div>
            </div>
            </th>
          </tr>
          <tr>
            <th><h5><i class="fa fa-bolt fa-fw"></i> Efficiency</h5></th>
            <th>
            <div class="progress progress-striped active progress-fix">
              <div id="b-yefficiency" class="progress-bar {if $GLOBAL.userdata.shares.valid > 0}progress-bar-info{else}progress-bar-warning{/if} black" style="width:{if $GLOBAL.userdata.shares.valid > 0}{(100 - ($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100))|number_format:"2"}%{else}100.00%{/if}" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar">{if $GLOBAL.userdata.shares.valid > 0}{(100 - ($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}</div>
            </div>
            </th>
            <th>
            <div class="progress progress-striped active progress-fix">
               <div id="b-pefficiency" class="progress-bar {if $GLOBAL.roundshares.valid > 0}progress-bar-info{else}progress-bar-warning{/if} black" style="width:{if $GLOBAL.roundshares.valid > 0}{(100 - ($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100))|number_format:"2"}%{else}100.00%{/if}" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar">{if $GLOBAL.roundshares.valid > 0}{(100 - ($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}</div>
            </div>
          </tr>
        </thead>
      </table>
