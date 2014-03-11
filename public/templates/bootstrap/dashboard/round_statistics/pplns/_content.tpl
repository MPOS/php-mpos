      <table class="table borderless m-b-none text-small">
        <thead>
          <tr>
            <th><h4><i class="fa fa-cloud fa-fw"></i> Round Shares</h4></th>
            <th><h4><i class="fa fa-thumbs-up fa-fw"></i> Valid</h4></th>
            <th><h4><i class="fa fa-thumbs-down fa-fw"></i> Invalid</h4></th>
            <th><h4><i class="fa fa-dot-circle-o fa-fw"></i> Efficiency</h4></th>
          </tr>
          <tr>
            <th><h4><i class="fa fa-user fa-fw"></i> My Shares</h4></td>
            <th><h4 id="b-yvalid">{$GLOBAL.userdata.shares.valid|number_format}</h4></th>
            <th><h4 id="b-yivalid">{$GLOBAL.userdata.shares.invalid|number_format}</h4></th>
            <th>
              <h4 id="b-yefficiency">{if $GLOBAL.userdata.shares.valid > 0}{(100 - ($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}</h4>
            </th>
          </tr>
          <tr>
            <th><h4><i class="fa fa-users fa-fw"></i> Pool Shares</h4></th>
            <th><h4 id="b-pvalid">{$GLOBAL.roundshares.valid|number_format}</h4></th>
            <th><h4 id="b-pivalid">{$GLOBAL.roundshares.invalid|number_format}</h4></th>
            <th>
              <h4 id="b-pefficiency">{if $GLOBAL.roundshares.valid > 0}{(100 - ($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}<h4>
            </th>
          </tr>
        </thead>
      </table>
