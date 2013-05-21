            <div class="block" style="clear:none; margin-top:15px; margin-left:13px;">
              <div class="block_head">
                <div class="bheadl"></div>
                <div class="bheadr"></div>
                <h1>Dashboard</h1>
              </div>
              <div class="block_content" style="padding-top:10px;">
                <table class="sidebar">
                    <tr><td colspan="2"><b>Your Current Hashrate</b></td></tr>
                    <tr><td colspan="2">{$GLOBAL.userdata.hashrate} KH/s</td></tr>
                    <tr>
                      <td colspan="2"><b><u>Unpaid Shares</u></b> <span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Submitted shares between the last 120 confirms block until now.'></span><td>
                    </tr>
                    <tr>
                      <td><b>Your Valid<b></td>
                      <td><i>{$GLOBAL.userdata.shares.valid}</i><font size='1px'></font></b></td>
                    </tr>
                    <tr>
                      <td><b>Pool Valid</td>
                      <td><i>{$GLOBAL.roundshares.valid}</i> <font size='1px'></font></b></td>
                    </tr>
                    <tr>
                      <td colspan="2"><b><u>Round Shares</u></b> <span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Submitted shares since last found block (ie. round shares)'></span></td>
                    </tr>
                    <tr>
                      <td><b>Pool Valid</b></td>
                      <td><i>{$GLOBAL.roundshares.valid}</i></td>
                    </tr>
                    <tr>
                      <td><b>Pool Invalid</b></td>
                      <td><i>{$GLOBAL.roundshares.invalid}</i></td>
                    </tr>
                    <tr>
                      <td><b>Your Invalid</b></td>
                      <td><i>{$GLOBAL.userdata.shares.invalid}</i><font size='1px'></font></td>
                    </tr>
                    <tr>
                      <td colspan="2"><b><u>Round Estimate</u></b></td>
                    </tr>
                    <tr>
                      <td><b>Block</b></td>
                      <td>{$GLOBAL.userdata.est_block} LTC</td>
                    </tr>
                    <tr>
                      <td><b>Fees</b></td>
                      <td>{$GLOBAL.userdata.est_fee} LTC</td>
                    </tr>
                    <tr>
                      <td><b>Donation</b></td>
                      <td>{$GLOBAL.userdata.est_donation} LTC</td>
                    </tr>
                    <tr>
                      <td><b>Payout</b></td>
                      <td>{$GLOBAL.userdata.est_payout} LTC</td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr><td colspan="2"><b><u>Account Balance</u></b></td></tr>
                    <tr><td colspan="2"><b>{$GLOBAL.userdata.balance|default:"0"} LTC</td></tr>
                  </table>
                </div>
              <div class="bendl"></div>
              <div class="bendr"></div>
            </div>
