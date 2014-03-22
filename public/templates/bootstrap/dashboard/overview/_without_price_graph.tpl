          <div class="col-md-spark-2 col-sm-6">
            <div class="circle-tile">
              <div class="circle-tile-heading blue">
                <i class="fa fa-male fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content blue">
                <div class="circle-tile-description text-faded">
                  <p class="h5">My Hashrate</p>
                  <div class="circle-tile-number text-faded up">
                    <span class="overview" id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</span>
                    <span class="overview-mhs"> {$GLOBAL.hashunits.personal}</span>
                    <br>
                    <span class="personal-hashrate-bar spark-18"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-spark-2 col-sm-6">
            <div class="circle-tile">
              <div class="circle-tile-heading blue">
                <i class="fa fa-users fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content blue">
                <div class="circle-tile-description text-faded">
                  <p class="h5">Pool Hashrate</p>
                  <div class="circle-tile-number text-faded up">
                    <span class="overview" id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</span>
                    <span class="overview-mhs"> {$GLOBAL.hashunits.pool}</span>
                    <br>
                    <span class="pool-hashrate-bar spark-18"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-spark-2 col-sm-6">
            <div class="circle-tile">
              <div class="circle-tile-heading blue">
                <i class="fa fa-share-square fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content blue">
                <div class="circle-tile-description text-faded">
                  <p class="h5">My Sharerate</p>
                  <div class="circle-tile-number text-faded up">
                    <span class="overview" id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</span>
                    <span class="overview-mhs"> S/s</span>
                    <br>
                    <span class="personal-sharerate-bar spark-18"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-spark-2 col-sm-6">
            <div class="circle-tile">
              <div class="circle-tile-heading blue">
               <i class="fa fa-truck fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content blue">
                <div class="circle-tile-description text-faded">
                  <p class="h5">Pool Workers</p>
                  <div class="circle-tile-number text-faded up">
                    <span class="overview" id="b-poolworkers">{$GLOBAL.workers}</span>
                    <br>
                    <span class="pool-workers-bar spark-18"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-spark-2 col-sm-6">
            <div class="circle-tile">
              <div class="circle-tile-heading blue">
               <i class="fa fa-h-square fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content blue">
                <div class="circle-tile-description text-faded">
                  <p class="h5">Net Hashrate</p>
                  <div class="circle-tile-number text-faded up">
                    <span class="overview" id="b-nethashrate">{$GLOBAL.nethashrate|number_format:"2"}</span>
                    <span class="overview-mhs"> {$GLOBAL.hashunits.network}</span>
                    <br>
                    <span class="pool-nethashrate-bar spark-18"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
