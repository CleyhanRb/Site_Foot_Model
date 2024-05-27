var changer = document.getElementById("color");

if (changer != null) {
    changer.addEventListener("change", function (){
        if (changer.checked) {
            document.documentElement.style.setProperty("--main-forecolor", "#101d46")
            document.documentElement.style.setProperty("--menu-backcolor-secondary", "#81848f")
        }else{
            document.documentElement.style.setProperty("--main-forecolor", "#831113")
            document.documentElement.style.setProperty("--menu-backcolor-secondary", "#9b6464")
        }
    })
}

function badid(text){
    var badidItem = document.getElementById("bad-id");
    badidItem.style.color = "red";

    if (text != null) {
        badidItem.textContent = text
    }
}