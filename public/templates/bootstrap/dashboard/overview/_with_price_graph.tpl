          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
            <div class="circle-tile-heading blue">
            <i class="fa fa-sitemap fa-fw fa-3x"></i>
            </div>
            <div class="circle-tile-content blue">
            <div class="circle-tile-description text-faded">
              <p>My Hashrate</p>
            <div class="circle-tile-number text-faded up">
              <span class="overview" id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</span><span class="overview-mhs"> {$GLOBAL.hashunits.personal}</span>
              <br>
              <span class="personal-hashrate-bar spark-18"></span>
            </div>
            </div>
            </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
            <div class="circle-tile-heading blue">
            <i class="fa fa-sitemap fa-fw fa-3x"></i>
            </div>
            <div class="circle-tile-content blue">
            <div class="circle-tile-description text-faded">
              <p>Pool Hashrate</p>
            </div>
            <div class="circle-tile-number text-faded up">
              <span class="overview" id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</span><span class="overview-mhs"> {$GLOBAL.hashunits.pool}</span>
              <br>
              <span class="pool-hashrate-bar spark-18"></span>
            </div>
            </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
            <div class="circle-tile-heading blue">
            <i class="fa fa-sitemap fa-fw fa-3x"></i>
            </div>
            <div class="circle-tile-content blue">
            <div class="circle-tile-description text-faded">
              <p>My Sharerate</p>
            </div>
            <div class="circle-tile-number text-faded up">
              <span class="overview" id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</span><span class="overview-mhs"> S/s</span>
              <br>
              <span class="personal-sharerate-bar spark-18"></span>
            </div>
            </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
            <div class="circle-tile-heading blue">
            <i class="fa fa-sitemap fa-fw fa-3x"></i>
            </div>
            <div class="circle-tile-content blue">
            <div class="circle-tile-description text-faded">
              <p>Pool Workers</p>
            </div>
            <div class="circle-tile-number text-faded up">
              <span class="overview" id="b-poolworkers">{$GLOBAL.workers}</span>
              <br>
              <span class="pool-workers-bar spark-18"></span>
            </div>
            </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
            <div class="circle-tile-heading blue">
            <i class="fa fa-sitemap fa-fw fa-3x"></i>
            </div>
            <div class="circle-tile-content blue">
            <div class="circle-tile-description text-faded">
              <p>Net Hashrate</p>
            </div>
            <div class="circle-tile-number text-faded up">
              <span class="overview" id="b-nethashrate">{$GLOBAL.nethashrate|number_format:"2"}</span><span class="overview-mhs"> {$GLOBAL.hashunits.network}</span>
              <br>
              <span class="pool-nethashrate-bar spark-18"></span>
            </div>
            </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
            <div class="circle-tile-heading blue">
            <i class="fa fa-sitemap fa-fw fa-3x"></i>
            </div>
            <div class="circle-tile-content blue">
            <div class="circle-tile-description text-faded">
              <p>{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}</p>
            </div>
            <div class="circle-tile-number text-faded up">
              <span class="overview" id="b-price">{$GLOBAL.price|default:"0"|number_format:"8"}</span>
              <br>
              <span class="coin-price-line spark-25"></span>
            </div>
            </div>
            </div>
          </div>

