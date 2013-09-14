          <div id="siteinfo">{$GLOBAL.website.name|default:"The Pool"}<br/>
            <span class="slogan">{$GLOBAL.website.slogan|default:"Resistance is Futile"}</span>
          </div>
          <div id="ministats">
            <table border="0">
              <tr>
                {if $GLOBAL.config.price.currency}<td><li>{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}: {$GLOBAL.price|default:"0"|number_format:"4"}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>{/if}
                <td><li>Network Hashrate: {$GLOBAL.nethashrate|default:"0"|number_format:"3"} {$GLOBAL.hashunits.network}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Hashrate: {$GLOBAL.hashrate|number_format:"3"} {$GLOBAL.hashunits.pool}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Sharerate: {$GLOBAL.sharerate|number_format:"2"} Shares/s&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
                <td><li>Pool Workers: {$GLOBAL.workers|default:"0"}&nbsp;&nbsp;&nbsp;&nbsp;</li></td>
              </tr>
            </table>
          </div>
