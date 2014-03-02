<article class="module width_full">
  <header><h3>Getting Started Guide</h3></header>
  <div class="module_content">
    <p>1. <strong>Create account.</strong></p>
      <ul>
        <li>Register <a href="{$smarty.server.SCRIPT_NAME}?page=register">here</a>, or login if you already have account</li>
        <li>Create a <a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers">worker</a> that will be used by the miner to login</li>
      </ul>
    </li>
    <p>2. <strong>Download a miner.</strong></p>
      <ul>
        <li><em>CGMiner Linux/Windows:</em> <a href="http://ck.kolivas.org/apps/cgminer/" target="_blank">Download here</a></li>
        <li><em>CGMiner Mac OS X:</em> <a href="http://spaceman.ca/cgminer/" target="_blank">Download here</a></li>
        <li><em>Asteroids GUI Miner Mac OS X:</em> <a href="http://www.asteroidapp.com/" target="_blank">Download here</a></li>
        <li><em>BFGMiner Linux/Windows:</em> <a href="http://bfgminer.org" target="_blank">Download here</a></li>
        <li><em>CPU Miner Mac/Linux/Windows:</em> precompiled binaries are available <a href="https://bitcointalk.org/index.php?topic=55038.msg654850#msg654850" target="_blank">Download here</a>.</li>
      </ul>
    </li>
    <p>3. <strong>Configure your miner.</strong></p>
      <p>Settings for Stratum (recommended):</p>
      <table width="50%">
        <tbody>
          <tr><td>STRATUM:</td><td><kbd>stratum+tcp://{$SITESTRATUMURL|default:$smarty.server.SERVER_NAME}</kbd></td></tr>
          <tr><td>PORT:</td><td><kbd>{$SITESTRATUMPORT|default:"3333"}</kbd></td></tr>
          <tr><td>Username:</td><td><kbd><em>Weblogin.Worker</em></kbd></td></tr>
          <tr><td>Password:</td><td><kbd><em>Worker Password</em></kbd></td></tr>
        </tbody>
      </table>
      <p>If you use a command-line miner, type:</p>
      <li>CGMiner</li>
      <pre>./cgminer {if $GLOBAL.config.algorithm == 'scrypt'}--scrypt{/if} -o stratum+tcp://{$SITESTRATUMURL|default:$smarty.server.SERVER_NAME}:{$SITESTRATUMPORT|default:"3333"} -u <em>Weblogin</em>.<em>Worker</em> -p <em>Worker password</em></pre>
      <li>BFGMiner</li>
      <pre>./bfgminer {if $GLOBAL.config.algorithm == 'scrypt'}--scrypt{/if} -o stratum+tcp://{$SITESTRATUMURL|default:$smarty.server.SERVER_NAME}:{$SITESTRATUMPORT|default:"3333"} -u <em>Weblogin</em>.<em>Worker</em> -p <em>Worker password</em></pre>
      <p>If you want, you can create additional workers with usernames and passwords of your choice <a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers">here</a></p>
    </li>
    <p>4. <strong>Create a {$SITECOINNAME|default:"Litecoin"} address to recieve payments.</strong></p>
      <ul>
        <li> Downloading the client &amp; block chain: 	Download the {$SITECOINNAME|default:"Litecoin"} client from <a href="{$SITECOINURL|default:"http://www.litecoin.org"}" target="_blank">here</a>.
          <p>Generate a new address and input it on your account page to receive payments.</p>
        </li>
      </ul>
    </li>
    <p>5. <strong>Advanced cgminer settings / FAQ</strong></p>
      <ul>
        <li><a href="https://github.com/ckolivas/cgminer/blob/master/SCRYPT-README" target="_blank">Scrypt readme</a></li>
        <li>Don't set <b>intensity</b> too high, I=11 is standard and safest. Higher intensity takes more GPU RAM. Check for <b>hardware errors</b> in cgminer (HW). HW=0 is good, otherwise lower intensity :)</li>
        <li>Set shaders according to the readme (or look at your graphic cards specifications). Cgminer uses this value at first run to calculate <b>thread-concurrency</b>. Easiest way to get this optimized is to use same settings as others have used here: <a href="http://litecoin.info/Mining_Hardware_Comparison">here</a>.</li>
        <li>There's also an interesting project which gives you a GUI for cgminer. Windows only it seems.</li>
        <li>Here's a great <a href="https://docs.google.com/document/d/1Gw7YPYgMgNNU42skibULbJJUx_suP_CpjSEdSi8_z9U/preview?sle=true" target="_blank">guide</a> how to get up and running with Xubuntu.</li>
      </ul>
    </li>
  </div>
</article>
