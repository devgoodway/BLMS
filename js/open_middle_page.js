function openPage(pageName) {
    var i;
    var x = document.getElementsByClassName("middle_page");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    document.getElementById(pageName).style.display = "block";
}
