{include file="global/block_header.tpl" BLOCK_HEADER="Getting Started Guide" BLOCK_STYLE="clear:none;"}
    <li>1. <strong>Create account.</strong>
      <ul>
        <li>Register <a href="{$smarty.server.PHP_SELF}?page=register">here</a>, or login if you already have account</li>
        <li>Create a <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">worker</a> that will be used by the miner to login</li>
      </ul>
    </li>
    <li>2. <strong>Download a miner.</strong>
      <ul>
        <li><em>Windows:</em> <a href="http://ck.kolivas.org/apps/cgminer/3.4/cgminer-3.4.3-windows.zip">Cgminer for Windows</a></li>
        <li><em>Linux:</em> <a href="http://ck.kolivas.org/apps/cgminer/3.4/cgminer-3.4.3-x86_64-built.tar.bz2">Cgminer for Linux (64bit)</a></li>
        <li><em>Mac OS X:</em> precompiled binaries are available <a href="https://bitcointalk.org/index.php?topic=55038.msg654850#msg654850">here</a>.</li>
      </ul>
    </li>
    <li>3. <strong>Configure your miner.</strong>
      <p>Settings for Stratum (recommended):</p>
      <table width="50%">
        <tbody>
          <tr><td>STRATUM:</td><td><kbd>stratum+tcp://{$smarty.server.SERVER_NAME}</kbd></td></tr>
          <tr><td>PORT:</td><td><kbd>3333</kbd></td></tr>
          <tr><td>Username:</td><td><kbd><em>Weblogin</em>.<em>Worker</em></kbd></td></tr>
          <tr><td>Password:</td><td><kbd>Worker Password</kbd></td></tr>
        </tbody>
      </table>
      <p>If you use a command-line miner, type:</p>
      <pre>./cgminer --scrypt -o stratum+tcp://{$smarty.server.SERVER_NAME}:3333 -u <em>Weblogin</em>.<em>Worker</em> -p <em>Worker password</em></pre>
      <p>If you want, you can create additional workers with usernames and passwords of your choice <a href="{$smarty.server.PHP_SELF}?page=account&action=workers">Here</a></p>
    </li>
    <li>4. <strong>Create a Litecoin address to recieve payments.</strong>
      <ul>
        <li> Downloading the client &amp; block chain: 	Download the Litecoin client from the <a href="http://www.litecoin.org/">here</a>.
          <p>Generate a new address and input it on your account page to receive payments.</p>
        </li>
      </ul>
    </li>
    <li>5. <strong>Advanced cgminer settings / FAQ</strong>
      <ul>
        <li><a href="https://github.com/ckolivas/cgminer/blob/master/SCRYPT-README">Scrypt readme</a></li>
        <li>Don't set <b>intensity</b> too high, I=11 is standard and safest. Higher intensity takes more GPU RAM. Check for <b>hardware errors</b> in cgminer (HW). HW=0 is good, otherwise lower intensity :)</li>
        <li>Set shaders according to the readme (or look at your graphic cards specifications). Cgminer uses this value at first run to calculate <b>thread-concurrency</b>. Easiest way to get this optimized is to use same settings as others have used here: <a href="http://litecoin.info/Mining_Hardware_Comparison">here</a>.</li>
        <li>There's also an interesting project which gives you a GUI for cgminer. Windows only it seems.</li>
        <li>Here's a great <a href="https://docs.google.com/document/d/1Gw7YPYgMgNNU42skibULbJJUx_suP_CpjSEdSi8_z9U/preview?sle=true">guide</a> how to get up and running with Xubuntu.</li>
      </ul>
    </li>
{include file="global/block_footer.tpl"}
