          <div id="siteinfo">{$GLOBAL.websitename}<br/>
            <span class="slogan">{$GLOBAL.slogan}</span>
          </div>
          <div id="ministats">
            <table border="0">
              <tr>
                {if $GLOBAL.config.price.currency}<td><li>{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}: {$GLOBAL.price|default:"n/a"|number_format:"4"}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>{/if}
                <td><li>Pool Hashrate: {($GLOBAL.hashrate / 1000)|number_format:"3"} MH/s&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Sharerate: {$GLOBAL.sharerate|number_format:"2"} Shares/s&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Workers: {$GLOBAL.workers}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
              </tr>
            </table>
          </div>
