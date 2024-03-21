function toggleMenu() {
    var menu = document.getElementById("nav-mobile");
    if (menu.style.display == "none") {
        menu.style.display = "flex";
    } else {
        menu.style.display = "none";
    }
    window.addEventListener("resize", function() {
        if (window.innerWidth > 1000) {
            menu.style.display = "none";
        }
    });
}
function pricingToggle(what) {
    var btn_yearly = document.getElementById("yearly");
    var btn_monthly = document.getElementById("monthly");
    var beginner_price = document.getElementById("beginner-price");
    var standard_price = document.getElementById("standard-price");
    var premium_price = document.getElementById("premium-price");
    time = document.getElementsByClassName("pricing__card__time");
    if (what === "monthly") {
        btn_yearly.classList.remove("pricing__btn-primary");
        btn_yearly.classList.add("pricing__btn-secondary");
        btn_monthly.classList.remove("pricing__btn-secondary");
        btn_monthly.classList.add("pricing__btn-primary");
        beginner_price.innerHTML = "0 €";
        standard_price.innerHTML = "19 €";
        premium_price.innerHTML = "36 €";
        for (var i = 0; i < time.length; i++) {
            time[i].innerHTML = " / month";
        }
    }
    else {
        btn_yearly.classList.remove("pricing__btn-secondary");
        btn_yearly.classList.add("pricing__btn-primary");
        btn_monthly.classList.remove("pricing__btn-primary");
        btn_monthly.classList.add("pricing__btn-secondary");
        beginner_price.innerHTML = "0 €";
        standard_price.innerHTML = "180 €";
        premium_price.innerHTML = "390 €";
        for (var i = 0; i < time.length; i++) {
            time[i].innerHTML = " / year";
        }
    }
}
function toggleBox(what) {
    var box = document.getElementById(what);
    var img = document.getElementById(what + "__img")
    if (box.style.height == "0rem") {
        box.style.height = "10rem";
        img.style.transform = "rotate(90deg)"
    } else {
        box.style.height = "0rem";
        img.style.transform = "rotate(0deg)"
    }
}
