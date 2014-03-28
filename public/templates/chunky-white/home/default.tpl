<div class="row">
  <div class="col-md-8">
    {section name=news loop=$NEWS}
        <section class="widget">
          <header class="post-header"><h5 class="article-header"><i class="fa fa-book"></i> {$NEWS[news].header}, posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</h5></header>
          <div class="body">
            {$NEWS[news].content}
          </div>
        </section>
    {/section}
  </div>

  <div class="col-md-4">
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
                  <div><span id="b-hashrate">{$GLOBAL.hashrate|number_format:"3"}</span> {$GLOBAL.hashunits.pool}</div>
                  <div class="progress progress-small">
                    <div class="progress-bar progress-bar-inverse" style="width: {($GLOBAL.hashrate|number_format:"3" / ($DIFFICULTY * 3)) * 100}%"></div>
                  </div>
              </div>
            </li>
              <li>
                  <div class="key pull-right">Workers</div>
                  <div class="stat">
                          <div>{$GLOBAL.workers}</div>
                          <div class="progress progress-small">
                            <div class="progress-bar" style="width: {($GLOBAL.workers / ($DIFFICULTY * 5)) * 100}%;"></div>
                          </div>
                      </div>
                  </li>
                  <li>
                      <div class="key pull-right">Network Difficulty</div>
                      <div class="stat">
                          <div>{$DIFFICULTY}</div>
                          <div class="progress progress-small">
                            <div class="progress-bar progress-bar-danger" style="width: {($DIFFICULTY / 250) * 100}%;"></div>
                          </div>
                      </div>
                  </li>
              </ul>
          </div>
      </section>
    </div>


