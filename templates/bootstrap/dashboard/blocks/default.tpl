{if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-desktop fa-fw"></i> {t}Last Found Blocks{/t}</h4>
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-bordered table-hover table-striped"> 
         <thead>
          <tr>
            <th class="text-right">{t}Height{/t}</th>
            <th class="text-center">{t}Finder{/t}</th>
            <th class="text-right">{t}Time{/t}</th>
            <th class="text-right">{t}Difficulty{/t}</th>
            <th class="text-right">{t}Amount{/t}</th>
            <th class="text-right">{t}Expected Shares{/t}</th>
            <th class="text-right">{t}Actual Shares{/t}</th>
            <th class="text-right">{t}Percentage{/t}</th>
          </tr>
          </thead>
          <tbody id="b-blocks">
{section block $BLOCKSFOUND}
            <tr>
              <td class="text-right">{$BLOCKSFOUND[block].height}</td>
              <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}{t}anonymous{/t}{else}{$BLOCKSFOUND[block].finder|default:"unknown"|escape}{/if}</td>
              <td class="text-right">{$BLOCKSFOUND[block].time|date_format:$GLOBAL.config.date}</td>
              <td class="text-right">{$BLOCKSFOUND[block].difficulty|number_format:"4"}</td>
              <td class="text-right">{$BLOCKSFOUND[block].amount|number_format:"2"}</td>
              <td class="text-right">{$BLOCKSFOUND[block].estshares|number_format}</td>
              <td class="text-right">{$BLOCKSFOUND[block].shares|number_format}</td>
              <td class="text-right">
                {math assign="percentage" equation="shares / estshares * 100" shares=$BLOCKSFOUND[block].shares|default:"0" estshares=$BLOCKSFOUND[block].estshares}
                <font color="{if ($percentage <= 100)}green{else}red{/if}">{$percentage|number_format:"2"}</font>
              </td>
            </tr>
{/section}
          </tbody>
        </table>
      </div>
      <div id="togglesound" class="togglesound">
        <div class="panel-footer text-right">
          <button id="muteButton" type="button" class="btn-xs btn-success toggleSoundButton"><i class="fa fa-volume-up"></i></button>
        </div>
      </div>
    </div>
  </div>
{/if}