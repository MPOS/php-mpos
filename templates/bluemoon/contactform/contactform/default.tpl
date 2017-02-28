<form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="contactform">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header">
          <div class="title">
            Contact Us
          </div>
          <span class="tools">
            <i class="fa fa-envelope"></i>
          </span>
        </div>
        <div class="widget-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="senderName">Your Name</label>
                <input type="text" class="form-control" name="senderName" value="" placeholder="Please type your name" size="15" maxlength="100" required />
              </div>
              <div class="form-group">
                <label for="senderEmail">Your Email Address</label>
                <input type="text" class="form-control" name="senderEmail" value="" placeholder="Please type your email" size="50"  maxlength="100" required />
              </div>
              <div class="form-group">
                <label for="senderEmail">Your Subject</label>
                <input type="text" class="form-control" name="senderSubject" value="" placeholder="Please type your subject" size="15" maxlength="100" required />
              </div>
              <div class="col-lg-6">
                <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="message">Your Message</label>
                <textarea type="text" class="form-control" name="senderMessage" cols="80" rows="20" maxlength="10000" required>{$smarty.request.senderMessage|escape|default:""}</textarea>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success btn-sm">Send Email</button>
          <button type="reset" class="btn btn-warning btn-sm">Reset Values</button></center>
        </div>
      </div>
    </div>
  </div>
</form>
