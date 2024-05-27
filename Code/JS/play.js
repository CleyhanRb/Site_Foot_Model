var betInput = document.getElementById("bet")
var earningsLabel = document.getElementById("earnings")
var profitLabel = document.getElementById("gains")
var selectedChoice = document.querySelector(".bet-option:has(> input:checked)")

var radios = document.querySelectorAll('input[type="radio"]');

Array.prototype.forEach.call(radios, function(el){
    el.addEventListener("change", function(){
        UpdateValue();
    })
})

betInput.addEventListener("input", function(e){
    if (e.target.value <= playerMoney && e.target.value > 0) {
        UpdateValue();
    }else if (e.target.value > playerMoney){
        e.target.value = playerMoney
    }else if (e.target.value <= 0){
        e.target.value = 0
    }else{
        e.target.value = 0
    }
})

UpdateValue()

function UpdateValue(){
    selectedChoice = document.querySelector(".bet-option:has(> input:checked)")
    if (selectedChoice != null) {
        var coef = selectedChoice.getElementsByClassName("coef")[0].textContent
        var bet = betInput.value
        console.log(coef)
        var earnings = Math.round(parseFloat(coef) * parseFloat(bet))
        earningsLabel.textContent = earnings
        profitLabel.textContent = Math.round(earnings - betInput.value)
    }
}

// CHECK BET VALUE

var submit = document.getElementById("submit")

submit.addEventListener("click", function(e){
    
    if (betInput.value > 0 && betInput.value <= playerMoney) {
        
        if (document.querySelectorAll('input:checked[type="radio"]').length == 1) {
            


        }else {
            alert("Veuillez faire votre choix.")
            e.preventDefault()
        }

    }else{
        alert("Veuillez entrer un nombre de crédit inférieur ou égal à la somme que vous possédez et supérieur à 0.")
        e.preventDefault();
    }

})