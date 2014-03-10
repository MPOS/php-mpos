<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading"><i class="fa fa-comments-o fa-fw"></i> Shoutbox
      </div>
      <div class="panel-body">
        <form method="post" id="form" role="form">
          <input type="hidden" id="nick" value="{$GLOBAL.userdata.username|escape}">
		  <div class="form-group">
		    <label>Message</label>
		    <input class="form-control" id="message" type="text" MAXLENGTH="255">
		  </div>
		    <input class="btn btn-success" id="send" type="submit" value="Shout it!">
		  </div>
	    </form>
	    <div class="row">
	      <div class="col-lg-12">
	        <div class="panel panel-info">
	          <div class="panel-heading"><i class="fa fa-comments-o fa-fw"></i> Latest Messages
	        </div>
	        <div class="panel-body">
	          <div class="content">
	            <div id="loading"><img src="{$PATH}/images/loading.gif" alt="Loading..."></div>
	            <ul>
	            <!--
	            {section name=shoutbox loop=$SHOUTBOX}
	              {$SHOUTBOX[shoutbox].user}
	            {/section}
	            -->
	            <ul>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<script type="text/javascript" src="{$PATH}/js/shoutbox.js"></script>
  </div>
</div>