<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-connectdevelop fa-fw"></i> {t}Peer Information{/t}
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th>{t}Host{/t}</th>
            <th>{t}Protocol{/t}</th>
            <th>{t}Identity{/t}</th>
            <th>{t}Connected{/t}</th>
            <th>{t}Traffic{/t}</th>
          </tr>
          </thead>
          <tbody>
{foreach key=KEY item=ARRAY from=$PEERINFO}
          <tr>
            <td>{$ARRAY['addr']}</td>
            <td>{$ARRAY['version']}</td>
            <td>{$ARRAY['subver']|replace:'/':''}</td>
            <td>{$ARRAY['conntime']|date_format:$GLOBAL.config.date}</td>
            <td>{(($ARRAY['bytessent'] + $ARRAY['bytesrecv']) / 1024 / 1024)|number_format:"3"} {t}MB{/t}</td>
          </tr>
{/foreach}
          </tbody>
        </table>
      </div>
    </div>
