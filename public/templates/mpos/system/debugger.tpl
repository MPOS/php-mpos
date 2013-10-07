
{if $DebuggerInfo}
    <!-- This will be loaded if we have debug information available -->
    <a href="#" id="toggle" class="toggle">Debugger Console</a>
    <div id="panel">
        <div id="DebuggerConsole">
          <br /><br />
            <table width="100%" class="tablesorter" cellspacing="0">
                <thead>
                    <tr>
                        <th width="15"><b>Level</b></th>
                        <th width="25"><b>Time</b></th>
                        <th width="*"><b>Message</b></th>
                        <th width="*"><b>Backtrace</b></th>
                    </tr>
                </thead>
                <tbody>
                    {section name=debug loop=$DebuggerInfo}
                        <tr class="{cycle values="even,odd"}">
                            <td align="center">{$DebuggerInfo[debug].level}</td>
                            <td align="right">{$DebuggerInfo[debug].time}</td>
                            <td align="left">{$DebuggerInfo[debug].message}</td>
                            <td align="left">
                                <table border="0">
                                {foreach from=$DebuggerInfo[debug].backtrace item=backtrace}
                                    <tr>
                                        <td style="display:inline-block; width:25px;">{$backtrace.line}</td>
                                        <td style="display:inline-block; width:200px">{$backtrace.file}</td>
                                        <td style="display:inline-block;">{$backtrace.function}</td>
                                    </tr>
                                {/foreach}
                                </table>
                            </td>
                        </tr>
                    {/section}
                </tbody>
            </table>

        </div>
    </div>

<script>{literal}
$(document).ready(function(){
    $("div#panel").hide();
    $("#toggle").click(function(){
        $("#panel").slideToggle("slow");
        $(this).toggleClass("active");
        return false;
    });
});
{/literal}</script>
{/if}
