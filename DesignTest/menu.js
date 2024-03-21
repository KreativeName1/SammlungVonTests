function menutoggle() {
    console.log('menu');
        var t = document.getElementById("menu");
        var grid = document.getElementById("grid");
        var bottom = document.getElementById("bottom");
        if(t.value=="ja")
        {
            grid.style.gridTemplateRows = "150px 1fr"
            bottom.style.display = "none";
            t.value="nein";
        }
        else if(t.value=="nein")
        {
            bottom.style.display = "block";
            grid.style.gridTemplateRows = "1fr 2fr"
            t.value="ja";
        }
}


function foo () {
    if ((window.Width || document.documentElement.clientWidth) >= 1000)
    {
        grid.style.gridTemplateRows = "1fr"
        bottom.style.display = "block";
    }
}
setInterval(foo, 500);