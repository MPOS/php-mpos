<form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="contactform">
  <div class="row">
    <div class="col-lg-6">
      <div class="panel panel-info">
        <div class="panel-heading">
          Contact Us
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="panel-body">
                <label for="senderName">Your Name</label>
                <input type="text" class="form-control" name="senderName" value="" placeholder="Please type your name" size="15" maxlength="100" required />
              </div>
              <div class="panel-body">
                <label for="senderEmail">Your Email Address</label>
                <input type="text" class="form-control" name="senderEmail" value="" placeholder="Please type your email" size="50"  maxlength="100" required />
              </div>
              <div class="panel-body">
                <label for="senderEmail">Your Subject</label>
                <input type="text" class="form-control" name="senderSubject" value="{$smarty.request.senderSubject|escape|default:""}" placeholder="Please type your subject" size="15" maxlength="100" required />
              </div>
              <div class="panel-body">
                <label for="message">Your Message</label>
                <textarea type="text" class="form-control" name="senderMessage" cols="80" rows="10" maxlength="10000" required>{$smarty.request.senderMessage|escape|default:""}</textarea>
              </div>
              <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}
              <button type="submit" class="btn btn-outline btn-success">Send Email</button>
              <button type="reset" class="btn btn-outline btn-warning">Reset Values</button></center>
            </div>
            <!-- /.col-lg-12 -->
          </div>
          <!-- /.panel -->
        </div>
        <!-- /.col-lg-6 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.row -->
  </div>
</form>
