	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-connectdevelop fa-fw"></i> Peer Information
				</div>
				<div class="panel-body no-padding">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							  <tr>
								<th>Host</th>
								<th>Protocol</th>
								<th>Identity</th>
								<th>Connected</th>
								<th>Traffic</th>
							  </tr>
							</thead>
							<tbody>
					{foreach key=KEY item=ARRAY from=$PEERINFO}
							  <tr>
								<td>{$ARRAY['addr']}</td>
								<td>{$ARRAY['version']}</td>
								<td>{$ARRAY['subver']|replace:'/':''}</td>
								<td>{$ARRAY['conntime']|date_format:$GLOBAL.config.date}</td>
								<td>{(($ARRAY['bytessent']|default:"0" + $ARRAY['bytesrecv']|default:"0") / 1024 / 1024)|number_format:"3"} MB</td>
							  </tr>
					{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
