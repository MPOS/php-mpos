<form action="{$smarty.server.PHP_SELF}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="contactform">
    <article class="widget col-md-8">
    <header><h3>Contact Us</h3></header>
    <div class="module_content">
      <div>
<p>These are the most common questions I see asked in IRC.</p>

<p><em>I have been mining for <strong>N</strong> hours and I still have 0 coins!</em>
<strong>No, you probably haven’t done anything wrong. We just haven't found a block since you started mining. Sometimes, it takes a while to find a block. You get a portion of the coins found in each block. Some blocks take minutes, some take hours. The current average time to find a block is around 4 hours. Our pool currently has 300 MH/s, so the blocks here take longer to find than, for example Dogehouse, but our blocks are split between a lot less people. So instead of getting a block every ten minutes where you get 1 coin out of it, you get a block every 4 hours with lots of coins in it. Over time, they pay out equivalently.</p>

<p><em>Why is this block taking so long?</em>
<strong>You can't predict when a block will be solved. It could be almost immediate, or it could take 10 times longer than the average estimated time for a block. This happens in all pools. We had two blocks solved this morning within 6 minutes of each other, with our estimate for a block around 2 hours. This weekend, we solved 16 blocks in 24 hours. It evens out over time.</strong></p>

<p><em>Doesn't it make more sense to join a pool with a higher hash rate?</em>
<strong>Maybe. If you are worried about what you make in the short term, then yes, pools with a higher hash rate will pay you lower, more consistent rewards. Pools with a lower hash rate pay out higher rewards to less people. You can get lucky in the short term and make more than the higher hash rate pool, or you can get unlucky and make less. This also evens out over time. The payout from two pools with a large gap in hash rate over 48-72 hours or so will be almost identical.</strong></p>

<p><em>Why did I only get <strong>N</strong> coins from that block?</em>
<strong>DOGE block rewards are random, currently between 0 and 1 million DOGE per block. Sometimes they will be very small, other times very large, and other times right around what you expect. Again, this evens out over time for an average of 500,000 per block.</strong></p>

<p><em>Can you reset my PIN?</em>
<strong>I can't reset it, but I can copy the hashed PIN from a second username. Email me both the username of the account with the lost PIN, and the username of a second, new account that has your desired PIN. You will need to verify that you own the original account.</strong></p>
      </div>
      <fieldset>
        <label for="senderName">Your Name</label>
        <input type="text" class="text tiny" name="senderName" value="{$smarty.request.senderName|escape|default:""}" placeholder="Please type your name" size="15" maxlength="100" required />
      </fieldset>
      <fieldset>
        <label for="senderEmail">Your Email Address</label>
        <input type="text" class="text tiny" name="senderEmail" value="{$smarty.request.senderEmail|escape|default:""}" placeholder="Please type your email" size="50"  maxlength="100" required />
      </fieldset>
      <fieldset>
        <label for="senderEmail">Your Subject</label>
        <input type="text" class="text tiny" name="senderSubject" value="{$smarty.request.senderSubject|escape|default:""}" placeholder="Please type your subject" size="15" maxlength="100" required />
      </fieldset>
      <fieldset>
        <label for="message">Your Message</label>
        <textarea type="text" name="senderMessage" cols="80" rows="10" maxlength="10000" required>{$smarty.request.senderMessage|escape|default:""}</textarea>
      </fieldset>
      <center>{nocache}{$RECAPTCHA|default:""}{/nocache}</center>
    </div>
    <div class="form-actions edit-actions">
      <div class="submit_link"><input type="submit" class="alt_btn" name="sendMessage" value="Send Email" /></div>
    </div>
  </article>
</form>
