$(function(){
    $(".widget-container").sortable({
        connectWith: '.widget-container',
        iframeFix: false,
        items: '.widget',
        opacity: 0.8,
        helper: 'original',
        revert: true,
        forceHelperSize: true,
        placeholder: 'widget widget-placeholder',
        forcePlaceholderSize: true,
        tolerance: 'pointer'
    });
});