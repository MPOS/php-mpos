<div class="row">
  {if $coin_name == 'SUM'}
  <div class="col-md-8">
<section class="widget">
  <header><h3>SummercoinV2 Getting Started Guide</h3></header>
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
          <tr><td><strong>(x11)</strong> STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:1137</kbd></td></tr>
          <tr><td><strong>(scrypt)</strong> STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:11000</kbd></td></tr>
          <tr><td><strong>(sha256)</strong> STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:11256</kbd></td></tr>
          <tr><td>Username:</td><td><kbd><em>Weblogin</em>.<em>Worker</em></kbd></td></tr>
          <tr><td>Password:</td><td><kbd>Worker Password</kbd></td></tr>
        </tbody>
      </table>
      <p>If you want, you can create additional workers with usernames and passwords of your choice <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">Here</a></p>
    </li>
    <li>4. <strong>Create a SummercoinV2 address to recieve payments.</strong>
      <ul>
        <li> Downloading the client &amp; block chain:  Download the SummercoinV2 client from <a href="https://bitcointalk.org/index.php?topic=625693.0" target="_blank">here</a>.
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
        <li>Since we are mining coins in exchange for SummercoinV2, your balance will show as 'Convertible' credits on the coin pages we are mining.</li>
        <li>Currently, you will receive a payout of these 'Convertible' credits that will show in your SummercoinV2 balance <strong>once per day</strong></li>
      </ul>
    </li>
  </div>
</section>
</div>
  {elseif $coin_name == 'HYPER'}
  <div class="col-md-8">
<section class="widget">
  <header><h3>HYPER Getting Started Guide</h3></header>
  <div class="module_content">
    <li>1. <strong>Create account.</strong>
      <ul>
        <li>Register <a href="{$smarty.server.PHP_SELF}?page=register">here</a>, or login if you already have account</li>
        <li>Create a <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">worker</a> that will be used by the miner to login</li>
      </ul>
    </li>
    <li>2. <strong>Download a miner.</strong>
      <ul>

        <li><em><strong>(scrypt)</strong> CGMiner Linux/Windows:</em> <a href="http://ck.kolivas.org/apps/cgminer/" target="_blank">Download here</a></li>
        <li><em><strong>(scrypt)</strong> CGMiner Mac OS X:</em> <a href="http://spaceman.ca/cgminer/" target="_blank">Download here</a></li>
        <li><em><strong>(X11)</strong> SGMiner</em> <a href="https://forums.chunkypools.com/t/guide-compiling-sgminer-for-x11-coins-in-ubuntu/28" target="_blank">Download here</a></li>
      </ul>
    </li>
    <li>3. <strong>Configure your miner.</strong>
      <p>Settings for Stratum (recommended):</p>
      <table width="100%">
        <tbody>
          <tr><td>scrypt STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:10000</kbd></td></tr>
          <tr><td>x11 STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:10011</kbd></td></tr>
          <tr><td>sha256 STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:10256</kbd></td></tr>
          <tr><td>Username:</td><td><kbd><em>Weblogin</em>.<em>Worker</em></kbd></td></tr>
          <tr><td>Password:</td><td><kbd>Worker Password</kbd></td></tr>
        </tbody>
      </table>
      <p>If you want, you can create additional workers with usernames and passwords of your choice <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">Here</a></p>
    </li>
    <li>4. <strong>Create a HYPER address to recieve payments.</strong>
      <ul>
        <li> Downloading the client &amp; block chain:  Download the HYPER client from <a href="http://www.hypercrypto.com" target="_blank">here</a>.
          <p>Generate a new address and input it on your account page to receive payments.</p>
        </li>
      </ul>
         <li>5. <strong>Balance info</strong>
      <ul>
        <li>Since we are mining coins in exchange for HYPER, your balance will show as 'Convertible' credits on the coin pages we are mining.</li>
        <li>Currently, you will receive a payout of these 'Convertible' credits that will show in your HYPER balance <strong>once per day</strong></li>
      </ul>
    </li>
  </div>
</section>
</div>
  {elseif $coin_name == 'WC'}
 <div class="col-md-8">
 <section class="widget widget-tabs">
          <header>
  <ul class="nav nav-tabs">
  <li class="active"><a href="#getting-started" data-toggle="tab">getting started</a></li>
  <li><a href="#news" data-toggle="tab">news</a></li>
  </ul>
</header>
  <div class="body tab-content">
    <div id="getting-started" class="tab-pane active">
      <h5><i class="fa fa-book"></i> Getting Started with the Whitecoin Multipool</h5>
            <p>We are proud to host the Whitecoin Multipool. We will mine the most profitable scrypt, x11, and sha256 coins with enough volume for exchange, and exchange those coins to Bitcoin and then from Bitcoin to Whitecoin for payouts.</p>

<p><code>SCRYPT: stratum+tcp://us-east.chunkypools.com:3337</code></p>

<p><code>X11: stratum+tcp://us-east.chunkypools.com:1177</code></p>

<p><code>SHA256: stratum+tcp://us-east.chunkypools.com:4447</code></p>

<p><strong>How does the multipool work?</strong></p>

<p>The multipool uses a profitability algorithm to determine which coin will return the most Whitecoin. As each coin's block is processed, you will receive non-withdrawable convertible credits that are converted to Whitecoin once per day. You can then withdraw from your Whitecoin account page as usual.</p>

<p><strong>How do I see my balance?</strong></p>

<p>You can see your convertible balance on the mined coin's page -- if we're mining Potcoin, look on the Potcoin dashboard.</p>

<p>Alternatively, you can view all balances consolidated on your <a href="/account" target="_blank">account page</a>.</p>

<p><strong>I can't find my workers!</strong></p>

<p>Again, you can check the mined coin's page, or check the consolidated <a href="/account" target="_blank">account page</a>. You won't see any worker info on the Whitecoin page for the time being because we are not mining Whitecoin directly.</p>

          </div>
    <div id="news" class="tab-pane">
    {section name=news loop=$NEWS}
        <div style="padding-bottom: 15px">
          <header><h5 class="article-header"><i class="fa fa-book"></i> {$NEWS[news].header}, posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</h5></header>
          <div class="body">
            {$NEWS[news].content}
          </div>
        </div>
    {/section}
    </div>
  </div>
  </div>
 {elseif empty($NEWS)}
   <div class="col-md-8">
     <section class="widget">
  <header><h3>Getting Started Guide</h3></header>
  <div class="module_content">
    <li>1. <strong>Create account.</strong>
      <ul>
        <li>Register <a href="{$smarty.server.PHP_SELF}?page=register">here</a>, or login if you already have account</li>
        <li>Create a <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">worker</a> that will be used by the miner to login</li>
      </ul>
    </li>
    <li>2. <strong>Download a miner.</strong>
      <ul>
        <li><em>CGMiner Linux/Windows:</em> <a href="http://ck.kolivas.org/apps/cgminer/" target="_blank">Download here</a></li>
        <li><em>CGMiner Mac OS X:</em> <a href="http://spaceman.ca/cgminer/" target="_blank">Download here</a></li>
        <li><em>CPU Miner Mac/Linux/Windows:</em> precompiled binaries are available <a href="https://bitcointalk.org/index.php?topic=55038.msg654850#msg654850" target="_blank">Download here</a>.</li>
      </ul>
    </li>
    <li>3. <strong>Configure your miner.</strong>
      <p>Settings for Stratum (recommended):</p>
      <table width="100%">
        <tbody>
          <tr><td>STRATUM:</td><td><kbd>stratum+tcp://us-east.chunkypools.com:3350</kbd></td></tr>
          <tr><td>Username:</td><td><kbd><em>Weblogin</em>.<em>Worker</em></kbd></td></tr>
          <tr><td>Password:</td><td><kbd>Worker Password</kbd></td></tr>
        </tbody>
      </table>
      <p>If you use a command-line miner, type:</p>
      <pre>./cgminer {if $GLOBAL.config.algorithm == 'scrypt'}--scrypt {/if} -o stratum+tcp://{$smarty.server.SERVER_NAME}:{$SITESTRATUMPORT|default:"3333"} -u <em>Weblogin</em>.<em>Worker</em> -p <em>Worker password</em></pre>
      <p>If you want, you can create additional workers with usernames and passwords of your choice <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">Here</a></p>
    </li>
    <li>4. <strong>Create a {$GLOBAL.config.currency} address to recieve payments.</strong>
      <ul>
        <li> Downloading the client &amp; block chain:  Download the {$GLOBAL.config.currency} client from <a href="http://www.silk-coin.com" target="_blank">here</a>.
          <p>Generate a new address and input it on your account page to receive payments.</p>
      </ul>
    </li>
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
  <section style="text-align: center; padding-bottom: 10px">
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
                  {if $GLOBAL.multipool}
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
                    {if $GLOBAL.multipool}
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
                          {if $GLOBAL.multipool}
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
      {if $GLOBAL.multipool}
      <section class="widget widget-tabs">
      <header>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#mining-scrypt" data-toggle="tab">scrypt</a></li>
          <li><a href="#mining-x11" data-toggle="tab">x11</a></li>
          <li><a href="#mining-sha256" data-toggle="tab">sha256</a></li>
        </ul>
      </header>
      <div class="body tab-content">
 	<div id="mining-scrypt" class="tab-pane active">
          <h5 class="tab-header"><i class="eicon-network"></i> currently mining</h5>
            <ul class="news-list">
              <a href="/{$scrypt_coin}/" target="_blank">
              <li>
                <img src="/images/{$scrypt_coin}.png" class="pull-left img-circle">
                <div class="news-item-info">
                  <div class="name">{$coin_dictionary[$scrypt_coin]}</div>
                </div>
              </li>
              </a>
            </ul>
	</div>
 	<div id="mining-x11" class="tab-pane">
          <h5 class="tab-header"><i class="eicon-network"></i> currently mining</h5>
            <ul class="news-list">
              <a href="/{$x11_coin}/" target="_blank">
              <li>
                <img src="/images/{$x11_coin}.png" class="pull-left img-circle">
                <div class="news-item-info">
                  <div class="name">{$coin_dictionary[$x11_coin]}</div>
                </div>
              </li>
              </a>
            </ul>
	</div>
 	<div id="mining-sha256" class="tab-pane">
          <h5 class="tab-header"><i class="eicon-network"></i> currently mining</h5>
            <ul class="news-list">
              <a href="/{$sha256_coin}/" target="_blank">
              <li>
                <img src="/images/{$sha256_coin}.png" class="pull-left img-circle">
                <div class="news-item-info">
                  <div class="name">{$coin_dictionary[$sha256_coin]}</div>
                </div>
              </li>
              </a>
            </ul>
	</div>
      </div>
      </section>
      
      <section class="widget widget-tabs">
      <header>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#connection-scrypt" data-toggle="tab">scrypt</a></li>
          <li><a href="#connection-x11" data-toggle="tab">x11</a></li>
          <li><a href="#connection-sha256" data-toggle="tab">sha256</a></li>
        </ul>
      </header>
      <div class="body tab-content">
 	<div id="connection-scrypt" class="tab-pane active">
          <h5 class="tab-header"><i class="fa fa-rocket"></i> Connection Strings</h5>
	<ul>
          {if $coin_name == 'WC'}
          <li><code>stratum+tcp://us-east.chunkypools.com:3337</code></li>
          {elseif $coin_name == 'BNS'}
          <li><code>stratum+tcp://us-east.chunkypools.com:8888</code></li>
          {elseif $coin_name == 'UVC'}
          <li><code>stratum+tcp://us-east.chunkypools.com:9999</code></li>
          {elseif $coin_name == 'HYPER'}
          <li><code>stratum+tcp://us-east.chunkypools.com:10000</code></li>
          {elseif $coin_name == 'SUM'}
          <li><code>stratum+tcp://us-east.chunkypools.com:11000</code></li>
          {/if}

        </ul>
      </div>
 	<div id="connection-x11" class="tab-pane">
          <h5 class="tab-header"><i class="fa fa-rocket"></i> Connection Strings</h5>
	<ul>
          {if $coin_name == 'WC'}
          <li><code>stratum+tcp://us-east.chunkypools.com:1177</code></li>
          {elseif $coin_name == 'HYPER'}
          <li><code>stratum+tcp://us-east.chunkypools.com:10011</code></li>
          {elseif $coin_name == 'SUM'}
          <li><code>stratum+tcp://us-east.chunkypools.com:1137</code></li>
          {/if}
        </ul>
      </div>
 	<div id="connection-sha256" class="tab-pane">
          <h5 class="tab-header"><i class="fa fa-rocket"></i> Connection Strings</h5>
	<ul>
          {if $coin_name == 'WC'}
          <li><code>stratum+tcp://us-east.chunkypools.com:4477</code></li>
          {elseif $coin_name == 'HYPER'}
          <li><code>stratum+tcp://us-east.chunkypools.com:10256</code></li>
          {elseif $coin_name == 'SUM'}
          <li><code>stratum+tcp://us-east.chunkypools.com:11256</code></li>
          {/if}
        </ul>
      </div>
      </div>
      </section>
      {/if}
    </div>





