          <div id="siteinfo">{$GLOBAL.websitename}<br/>
            <span class="slogan">{$GLOBAL.slogan}</span>
          </div>
          <div id="ministats">
            <table border="0">
              <tr>
                <td><li>LTC/usd: {$GLOBAL.price|default:"n/a"}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Hashrate: {$GLOBAL.hashrate / 1000} MH/s&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Sharerate: {$GLOBAL.sharerate} Shares/s&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Workers: {$GLOBAL.workers}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
              </tr>
            </table>
          </div>
