export function headerMenuHandler() {
    $(".fa-bars").click(function(){
        $(".responsive-nav-links").fadeToggle(300);
    });

    $(".responsive-nav-links li").click(function(){
        $(".responsive-nav-links").fadeToggle(300);
    });
}

$(document).ready(function(){
    headerMenuHandler();
})