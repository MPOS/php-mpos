<div class="row">
  {if $coin_name == 'SUM'}
  <div class="col-md-8">
<section class="widget">
  <header><h3>Summercoin Getting Started Guide</h3></header>
  <div class="module_content">
    <li>1. <strong>Create account.</strong>
      <ul>
        <li>Register <a href="{$smarty.server.PHP_SELF}?page=register">here</a>, or login if you already have account</li>
        <li>Create a <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">worker</a> that will be used by the miner to login</li>
      </ul>
    </li>
    <li>2. <strong>Download a miner.</strong>
      <ul>
        <li><em>SGMiner X11</em> <a href="https://forums.chunkypools.com/t/guide-compiling-sgminer-for-x11-coins-in-ubuntu/28" target="_blank">Download here</a></li>
      </ul>
    </li>
    <li>3. <strong>Configure your miner.</strong>
      <p>Settings for Stratum (recommended):</p>
      <table width="100%">
        <tbody>
          <tr><td>STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:1137</kbd></td></tr>
          <tr><td>Username:</td><td><kbd><em>Weblogin</em>.<em>Worker</em></kbd></td></tr>
          <tr><td>Password:</td><td><kbd>Worker Password</kbd></td></tr>
        </tbody>
      </table>
      <p>If you want, you can create additional workers with usernames and passwords of your choice <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">Here</a></p>
    </li>
    <li>4. <strong>Create a Summercoin address to recieve payments.</strong>
      <ul>
        <li> Downloading the client &amp; block chain:  Download the Summercoin client from <a href="https://bitcointalk.org/index.php?topic=581065.0" target="_blank">here</a>.
          <p>Generate a new address and input it on your account page to receive payments.</p>
        </li>
      </ul>
          <li>5. <strong>Advanced cgminer settings / FAQ</strong>
      <ul>
        <li><a href="https://github.com/ckolivas/cgminer/blob/master/SCRYPT-README" target="_blank">Scrypt readme</a></li>
        <li>Don't set <b>intensity</b> too high, I=11 is standard and safest. Higher intensity takes more GPU RAM. Check for <b>hardware errors</b> in cgminer (HW). HW=0 is good, otherwise lower intensity :)</li>
        <li>Set shaders according to the readme (or look at your graphic cards specifications). Cgminer uses this value at first run to calculate <b>thread-concurrency</b>. Easiest way to get this optimized is to use same settings as others have used here: <a href="http://litecoin.info/Mining_Hardware_Comparison">here</a>.</li>
        <li>There's also an interesting project which gives you a GUI for cgminer. Windows only it seems.</li>
        <li>Here's a great <a href="https://docs.google.com/document/d/1Gw7YPYgMgNNU42skibULbJJUx_suP_CpjSEdSi8_z9U/preview?sle=true" target="_blank">guide</a> how to get up and running with Xubuntu.</li>
      </ul>
         <li>6. <strong>Balance info</strong>
      <ul>
        <li>Since we are mining coins in exchange for Summercoin, your balance will show as 'Convertible' credits on the coin pages we are mining.</li>
        <li>Currently, you will receive a payout of these 'Convertible' credits that will show in your Summercoin balance <strong>once per day</strong></li>
      </ul>
    </li>
  </div>
</section>
</div>
  {else}
  <div class="col-md-8">
    {section name=news loop=$NEWS}
        <section class="widget">
          <header><h5 class="article-header"><i class="fa fa-book"></i> {$NEWS[news].header}, posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</h5></header>
          <div class="body">
            {$NEWS[news].content}
          </div>
        </section>
    {/section}
  </div>
  {/if}

  <div class="col-md-4">
  <section style="text-align: center;">
    <img src="https://chunkypools.com/images/{$coin_name}.png" style="width: 300px;">
  </section>
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
                  {if $coin_name == 'WC' or $coin_name == 'SUM'}
                    <div><span id="b-hashrate">{$coin_hash_rate|number_format:"3"}</span> {$GLOBAL.hashunits.pool}</div>
                    <div class="progress progress-small">
                    <div class="progress-bar progress-bar-success" style="width: {$coin_hash_rate|number_format:"3" / 25}%"></div>
                  </div>
                  {else}
                    <div><span id="b-hashrate">{$GLOBAL.hashrate|number_format:"3"}</span> {$GLOBAL.hashunits.pool}</div>
                    <div class="progress progress-small">
                    <div class="progress-bar progress-bar-success" style="width: {($GLOBAL.hashrate|number_format:"3" / ($DIFFICULTY * 3)) * 100}%"></div>
                  </div>
                  {/if}
              </div>
            </li>
              <li>
                  <div class="key pull-right">Workers</div>
                  <div class="stat">
                    {if $coin_name == 'WC' or $coin_name == 'SUM'}
                    <div>{$coin_workers}</div>
                      <div class="progress progress-small">
                        <div class="progress-bar" style="width: {$coin_workers / 25}%;"></div>
                      </div>
                    </div>
                    {else}
                      <div>{$GLOBAL.workers}</div>
                      <div class="progress progress-small">
                        <div class="progress-bar" style="width: {($GLOBAL.workers / ($DIFFICULTY * 5)) * 100}%;"></div>
                      </div>
                    </div>
                    {/if}
                  </li>
                  <li>
                      <div class="key pull-right">Network Difficulty</div>
                      <div class="stat">
                          {if $coin_name == 'WC'}
                          <div>0</div>
                          {else}
                          <div>{$DIFFICULTY|default:'0'}</div>
                          {/if}
                          <div class="progress progress-small">
                            <div class="progress-bar progress-bar-danger" style="width: {($DIFFICULTY / 250) * 100}%;"></div>
                          </div>
                      </div>
                  </li>
              </ul>
          </div>
      </section>
      {if $coin_name == 'WC' or $coin_name == 'SUM'}
      <section class="widget">
      <header>
        <h4><i class="eicon-network"></i> Currently Mining</h4>
      </header>
      <div class="body">
        <ul class="news-list">
          {if $coin_name == 'WC'}
          <li>
            <img src="/wc/site_assets/chunky-white/images/potcoin120.png" class="pull-left img-circle">
            <div class="news-item-info">
              <div class="name">POTCOIN</div>
            </div>
          </li>
          {else}
          <li>
            <img src="/wc/site_assets/chunky-white/images/muniti120.png" class="pull-left img-circle">
            <div class="news-item-info">
              <div class="name">MUNITI</div>
            </div>
          </li>
          {/if}
        </ul>
      </div>
      </section>
      
      <section class="widget">
      <header>
        <h4><i class="fa fa-rocket"></i> Connection Strings</h4>
      </header>
      <div class="body">
	<ul>
          {if $coin_name == 'WC'}
          <li><code>stratum+tcp://us-east.chunkypools.com:3337</code></li>
          <li><code>stratum+tcp://europe.chunkypools.com:3337</code></li>
          {else}
          <li><code>stratum+tcp://us-east.chunkypools.com:1137</code></li>
          <li><code>stratum+tcp://europe.chunkypools.com:1137</code></li>
          {/if}
        </ul>
      </div>
      </section>
      {/if}
    </div>





