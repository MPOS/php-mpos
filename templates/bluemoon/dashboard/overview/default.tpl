      <div class="wrapper">
        <div class="widget no-margin">
          <div class="widget-header">
            <div class="title">Pool Information</div>
          </div>
        </div>
        <ul class="stats">
          <li>
            <div class="left">
              <h4>
                <span class="overview" id="b-nethashrate">{if $GLOBAL.nethashrate > 0}{$GLOBAL.nethashrate|number_format:"2"}{else}n/a{/if}</span>
                <span class="overview-mhs"> {$GLOBAL.hashunits.network}</span>
              </h4>
              <p>Net Hashrate</p>
            </div>
            <div class="chart">
              <span class="pool-nethashrate-bar"></span>
            </div>
          </li>
          <li>
            <div class="left">
              <h4>
                <span class="overview" id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</span>
                <span class="overview-mhs"> {$GLOBAL.hashunits.personal}</span>
              </h4>
              <p>My Hashrate</p>
            </div>
            <div class="chart">
              <span class="personal-hashrate-bar"></span>
            </div>
          </li>
          <li>
            <div class="left">
              <h4>
                <span class="overview" id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</span>
                <span class="overview-mhs"> {$GLOBAL.hashunits.pool}</span>
              </h4>
              <p>Pool Hashrate</p>
            </div>
            <div class="chart">
              <span class="pool-hashrate-bar"></span>
            </div>
          </li>
          <li>
            <div class="left">
              <h4>
                <span class="overview" id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</span>
                <span class="overview-mhs"> S/s</span>
              </h4>
              <p>My Sharerate</p>
            </div>
            <div class="chart">
              <span class="personal-sharerate-bar"></span>
            </div>
          </li>
          <li>
            <div class="left">
              <h4>
                <span class="overview" id="b-poolworkers">{$GLOBAL.workers}</span>
              </h4>
              <p>Pool Workers</p>
            </div>
            <div class="chart">
              <span class="pool-workers-bar"></span>
            </div>
          </li>
          {if $GLOBAL.config.price.enabled}
          <li>
            <div class="left">
              <h4>
                <span class="overview" id="b-price">{$GLOBAL.price|default:"0"|number_format:"8"}</span>
              </h4>
              <p>{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}</p>
            </div>
            <div class="chart">
              <span class="coin-price-line"></span>
            </div>
          </li>
          {/if}
        </ul>
      </div>