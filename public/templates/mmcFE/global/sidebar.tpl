              <div class="block" style="clear:none; margin-top:15px; margin-left:13px;">
                <div class="block_head">
                  <div class="bheadl"></div>
                  <div class="bheadr"></div>
                  <h1>Dashboard</h1>
                </div>
                <div class="block_content" style="padding-top:10px;">
                  <p>
                    <b><u>Your Current Hashrate</u></b><br/>
                    <i><b>{$GLOBAL.userdata.hashrate} KH/s</b></i><br/><br/>
                      <u><b>Unpaid Shares</b></u><span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Submitted shares between the last 120 confirms block until now.'></span><br/>
                      Your Valid: <b><i>{$GLOBAL.userdata.shares.valid}</i><font size='1px'></font></b><br/>
                      Pool Valid: <b><i>{$GLOBAL.roundshares.valid}</i> <font size='1px'></font></b><br/><br>
                      <u><b>Round Shares </b></u><span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Submitted shares since last found block (ie. round shares)'></span><br/>
                      Pool Valid: <b><i>{$GLOBAL.roundshares.valid}</i></b><br>
                      Pool Inalid: <b><i>{$GLOBAL.roundshares.invalid}</i></b><br>
                      Your Invalid: <b><i>{$GLOBAL.userdata.shares.invalid}</i><font size='1px'></font></b><br/><br>
                      <u><b>Round Estimate</b></u><font size='1'></font></u><br>
                      <b><i>{math equation="round(( x / y ) * z, 8)" x=$GLOBAL.userdata.shares.valid y=$GLOBAL.roundshares.valid z=$GLOBAL.reward}</i> <font size='1px'>LTC</font></b><br><br>
                      <u><b>Account Balance</b></u><br><b><i>{$GLOBAL.userdata.balance|default:"0"}</i><font size='1px'> LTC</font></b><br/><br>
                    </p>
                  </div>
                  <div class="bendl"></div>
                  <div class="bendr"></div>
                </div>
