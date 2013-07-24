                <table width="100%">
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td colspan="2"><b><u>Your Stats</u></b></td>
                    </tr>
                    <tr>
                      <td><b>Hashrate</b></td>
                      <td align="right">{$GLOBAL.userdata.hashrate|number_format} KH/s</td>
                    </tr>
                    <tr>
                      <td><b>Share Rate</b></td>
                      <td align="right">{$GLOBAL.userdata.sharerate|number_format:"2"} S/s</td>
                    </tr>
                    <tr>
                      <td><b>PPS Value</b></td>
                      <td align="right">{$GLOBAL.ppsvalue}</td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td colspan="2"><b><u>Round Shares</u></b></td>
                    </tr>
                    <tr>
                      <td><b>Pool Valid</b></td>
                      <td align="right">{$GLOBAL.roundshares.valid|number_format}</td>
                    </tr>
                    <tr>
                      <td><b>Pool Invalid</b></td>
                      <td align="right"><i>{$GLOBAL.roundshares.invalid|number_format}</i>{if $GLOBAL.roundshares.valid > 0}<font size='1px'> ({($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%)</font>{/if}</td>
                    </tr>
                    <tr>
                      <td><b>Your Invalid</b></td>
                      <td align="right"><i>{$GLOBAL.userdata.shares.invalid|number_format}</i>{if $GLOBAL.roundshares.valid > 0}<font size='1px'> ({($GLOBAL.userdata.shares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%)</font>{/if}</td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr><td colspan="2"><b><u>{$GLOBAL.config.currency} Estimates</u></b></td></tr>
                    <tr>
                      <td><b>in 24 hours</b></td>
                      <td align="right">{($GLOBAL.userdata.sharerate * 24 * 60 * 60 * $GLOBAL.ppsvalue)|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td><b>in 7 days</b></td>
                      <td align="right">{($GLOBAL.userdata.sharerate * 7 * 24 * 60 * 60 * $GLOBAL.ppsvalue)|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td><b>in 14 days</b></td>
                      <td align="right">{($GLOBAL.userdata.sharerate * 14 * 24 * 60 * 60 * $GLOBAL.ppsvalue)|number_format:"8"}</td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr><td colspan="2"><b><u>{$GLOBAL.config.currency} Account Balance</u></b></td></tr>
                    <tr><td>Confirmed</td><td align="right"><b>{$GLOBAL.userdata.balance.confirmed|default:"0"|number_format:"3"}</td></tr>
                    <tr><td>Unconfirmed</td><td align="right"><b>{$GLOBAL.userdata.balance.unconfirmed|default:"0"|number_format:"3"}</td></tr>
                  </table>
