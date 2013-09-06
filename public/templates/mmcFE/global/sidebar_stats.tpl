            <div class="block" style="clear:none; margin-top:15px; margin-left:13px;">
              <div class="block_head">
                <div class="bheadl"></div>
                <div class="bheadr"></div>
                <h1>{$GLOBAL.config.currency} Global Stats</h1>
              </div>
              <div class="block_content" style="padding-top:10px;">
                  <table class="sidebar" style="width: 196px">
                    <tr>
                      <td colspan="2"><b><u>{$GLOBAL.config.currency} Stats</u></b></td>
                    </tr>
                    <tr>
                      <td><b>Difficulty</b></td>
                      <td class="right">{$GLOBAL.difficulty}</td>
                    </tr>
                    <tr>
                      <td><b>Hashrate</b></td>
                      <td class="right">{($GLOBAL.nethashrate / 1000 / 1000 )|default:"0"|number_format:"3"}MH/s</td>
                    </tr>
                    <tr>
                      <td><b>{$GLOBAL.config.currency} Price</b></td>
                      <td class="right">{$GLOBAL.price}</td>
                    </tr>
                  </table>
               </div>
              <div class="bendl"></div>
              <div class="bendr"></div>
            </div>
