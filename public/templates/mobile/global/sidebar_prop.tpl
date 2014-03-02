                <table width="100%">
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td colspan="2"><b><u>Your Stats</u></b></td>
                    </tr>
                    <tr>
                      <td><b>Hashrate</b></td>
                      <td align="right">{$GLOBAL.userdata.hashrate|number_format} {$GLOBAL.hashunits.personal}</td>
                    </tr>
                    <tr>
                      <td><b>Share Rate</b></td>
                      <td align="right">{$GLOBAL.userdata.sharerate|number_format:"2"} S/s</td>
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
                      <td><b>Your Valid<b></td>
                      <td align="right">{$GLOBAL.userdata.shares.valid|number_format}<font size='1px'></font></b></td>
                    </tr>
                    <tr>
                      <td><b>Pool Invalid</b></td>
                      <td align="right"><i>{$GLOBAL.roundshares.invalid|number_format}</i>{if $GLOBAL.roundshares.valid > 0}<font size='1px'> ({($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%)</font>{/if}</td>
                    </tr>
                    <tr>
                      <td><b>Your Invalid</b></td>
                      <td align="right"><i>{$GLOBAL.userdata.shares.invalid|number_format}</i>{if $GLOBAL.roundshares.valid > 0}<font size='1px'> ({($GLOBAL.userdata.shares.invalid /  $GLOBAL.userdata.shares.valid * 100)|number_format:"2"}%)</font>{/if}</td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td colspan="2"><b><u>{$GLOBAL.config.currency} Round Estimate</u></b></td>
                    </tr>
                    <tr>
                      <td><b>Block</b></td>
                      <td align="right">{$GLOBAL.userdata.estimates.block|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td><b>Fees</b></td>
                      <td align="right">{$GLOBAL.userdata.estimates.fee|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td><b>Donation</b></td>
                      <td align="right">{$GLOBAL.userdata.estimates.donation|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td><b>Payout</b></td>
                      <td align="right">{$GLOBAL.userdata.estimates.payout|number_format:"8"}</td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr><td colspan="2"><b><u>{$GLOBAL.config.currency} Account Balance</u></b></td></tr>
                    <tr><td>Confirmed</td><td align="right"><b>{$GLOBAL.userdata.balance.confirmed|default:"0"|number_format:"8"}</td></tr>
                    <tr><td>Unconfirmed</td><td align="right"><b>{$GLOBAL.userdata.balance.unconfirmed|default:"0"|number_format:"8"}</td></tr>
                  </table>
