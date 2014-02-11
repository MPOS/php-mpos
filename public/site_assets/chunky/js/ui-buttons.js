$(function(){
    $("button").tooltip();

    $('.selectpicker').selectpicker();
    //selectpicker doesn't seem to be flexible enough (can't change template), so need to replace span.caret externally
    $('.selectpicker + .bootstrap-select span.caret').replaceWith("<i class='fa fa-caret-down'></i>");
    $('.selectpicker + .bootstrap-select span.pull-left').removeClass("pull-left");
});