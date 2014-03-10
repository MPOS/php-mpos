          <div class="col-md-spark">
              <p class="overview">My Hashrate</p>
              <span class="overview" id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</span><span> {$GLOBAL.hashunits.personal}</span>
              <br>
              <span class="personal-hashrate-bar"></span>
          </div>
          <div class="col-md-spark">
              <p class="overview">My Sharerate</p>
              <span class="overview" id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</span>
              <br>
              <span class="personal-sharerate-bar"></span>
          </div>
          <div class="col-md-spark">
              <p class="overview">Pool Hashrate</p>
              <span class="overview" id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</span><span> {$GLOBAL.hashunits.pool}</span>
              <br>
              <span class="pool-hashrate-bar"></span>
          </div>
          <div class="col-md-spark">
              <p class="overview">Pool Workers</p>
              <span class="overview" id="b-poolworkers">{$GLOBAL.workers}</span>
              <br>
              <span class="pool-workers-bar"></span>
          </div>
          <div class="col-md-spark">
              <p class="overview">Net Hashrate</p>
              <span class="overview" id="b-nethashrate">{$GLOBAL.nethashrate|number_format:"2"}</span><span> {$GLOBAL.hashunits.network}</span>
              <br>
              <span class="pool-nethashrate-bar"></span>
          </div>
          <div class="col-md-spark">
              <p class="overview">{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}</p>
              <span class="overview" id="b-price">{$GLOBAL.price|default:"0"|number_format:"8"}</span>
              <br>
              <span class="coin-price-line"></span>
          </div>

