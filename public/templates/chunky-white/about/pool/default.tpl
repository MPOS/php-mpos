<header><h3>{$GLOBAL.website.name} FAQ</h3></header>
<div class="row">
  <div class="col-md-8">
    <article class="module">
      <p>These are the most common questions I see asked in IRC.</p>

      <div>
        <p><em>I have been mining for <strong>N</strong> hours and I still have 0 coins?</em><p>
        <p>No, you probably haven’t done anything wrong. We just haven't found a block since you started mining. Sometimes, it takes a while to find a block. You get a portion of the coins found in each block. Some blocks take minutes, some take hours. The current average time to find a block is around 4 hours. Our pool currently has 300 MH/s, so the blocks here take longer to find than, for example Dogehouse, but our blocks are split between a lot less people. So instead of getting a block every ten minutes where you get 1 coin out of it, you get a block every 4 hours with lots of coins in it. Over time, they pay out equivalently.</p>
      </div>

      <div>
        <p><em>Why is this block taking so long?</em></p>
        <p>You can't predict when a block will be solved. It could be almost immediate, or it could take 10 times longer than the average estimated time for a block. This happens in all pools. We had two blocks solved this morning within 6 minutes of each other, with our estimate for a block around 2 hours. This weekend, we solved 16 blocks in 24 hours. It evens out over time.</p>
      </div>

      <div>
        <p><em>Doesn't it make more sense to join a pool with a higher hash rate?</em></p>
        <p>Maybe. If you are worried about what you make in the short term, then yes, pools with a higher hash rate will pay you lower, more consistent rewards. Pools with a lower hash rate pay out higher rewards to less people. You can get lucky in the short term and make more than the higher hash rate pool, or you can get unlucky and make less. This also evens out over time. The payout from two pools with a large gap in hash rate over 48-72 hours or so will be almost identical.</p>
      </div>

      <div>
        <p><em>Why did I only get <strong>N</strong> coins from that block?</em></p>
        <p>DOGE block rewards are random, currently between 0 and 1 million DOGE per block. Sometimes they will be very small, other times very large, and other times right around what you expect. Again, this evens out over time for an average of 500,000 per block.</p>
      </div>

      <div>
        <p><em>Can you reset my PIN?</em></p>
        <p>You can reset it through the <a href="{$smarty.server.PHP_SELF}?page=password">password reset page</a> now.<p>
      </div>

      <div>
        <p><em>Why is the dashboard showing a different hashrate than cgminer?</em></p>
        <p>The dashboard uses your submitted shares to <strong>TRY</strong> to come up with an <strong>ESTIMATED HASHRATE</strong>. It is woefully inaccurate at times, and sometimes we may even make it more inaccurate on purpose because it's a hell of a load on the database.<p>
        <p>The bottom line is that: <strong>the dashboard only shows an estimate, which is not used in any way for the calculation of your payout.</strong> If you want to know your real hash rate, look at cgminer.</p>
      </div>
    </article>
  </div>
  <div class="col-md-4">
    <section class="widget">
      <header>
        <h4>
          <i class="fa fa-magnet"></i>
           Pool Overview
        </h4>
      </header>
      <div class="body">
        <ul class="server-stats">
          <li>
            <div class="key pull-right">Hashrate</div>
            <div class="stat">
                <div><span id="b-hashrate">{$GLOBAL.hashrate|number_format:"3"}</span> {$GLOBAL.hashunits.pool}</div>
                <div class="progress progress-small">
                  <div class="progress-bar progress-bar-inverse" style="width: {($GLOBAL.hashrate|number_format:"3" / ($DIFFICULTY * 3)) * 100}%"></div>
                </div>
            </div>
          </li>
          <li>
              <div class="key pull-right">Workers</div>
              <div class="stat">
                      <div>{$GLOBAL.workers}</div>
                      <div class="progress progress-small">
                        <div class="progress-bar" style="width: {($GLOBAL.workers / ($DIFFICULTY * 5)) * 100}%;"></div>
                      </div>
                  </div>
          </li>
          <li>
              <div class="key pull-right">Network Difficulty</div>
              <div class="stat">
                  <div>{$DIFFICULTY}</div>
                  <div class="progress progress-small">
                    <div class="progress-bar progress-bar-danger" style="width: {($DIFFICULTY / 250) * 100}%;"></div>
                  </div>
              </div>
          </li>
        </ul>
      </div>
    </section>
  </div>
</div>
