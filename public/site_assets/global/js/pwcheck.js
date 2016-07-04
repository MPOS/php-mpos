function calcScore(pass) {
  var keywords = [ "mine", "mining", "crypto", "pool", "coin" ];
  return zxcvbn(pass, keywords).score;
}

function getPassStrength(score) {
  if (score === 4)
    return "Excellent";
  if (score === 3)
    return "Strong";
  if (score === 2)
    return "Good";
  if (score === 1)
    return "Weak";
  if (score === 0)
    return "Extremely weak";
  return "";
}

function getStrengthColor(score) {
  if (score === 4)
    return "#390";
  if (score === 3)
    return "#3C0";
  if (score === 2)
    return "#399";
  if (score === 1)
    return "#E00";
  if (score === 0)
    return "#C00";
  return "#999";
}

function checkIfPasswordsMatch(password1, password2) {
  var pwMatch = $("#pw_match");
  if (password1 === password2 && password1 !== "" && password2 !== "") {
    $(pwMatch).text("Passwords match!");
    $(pwMatch).css("color", "#390");
  } else if (password1 === "" || password2 === "") {
    $(pwMatch).text("");
  } else {
    $(pwMatch).text("Passwords don't match!");
    $(pwMatch).css("color", "#399");
  }
}

$(document).ready(function() {
  var pwField1 = $("#pw_field");
  var pwField2 = $("#pw_field2");
  
  if(pwField1.length > 0 && pwField2.length > 0){
    (function(){var a;a=function(){var a,b;b=document.createElement("script");b.src=zxcvbnPath;b.type="text/javascript";b.async=!0;a=document.getElementsByTagName("script")[0];return a.parentNode.insertBefore(b,a)};null!=window.attachEvent?window.attachEvent("onload",a):window.addEventListener("load",a,!1)}).call(this);
  }

  $(pwField1).add(pwField2).on("keypress keyup keydown", function() {
    var password1 = $(pwField1).val();
    var password2 = $(pwField2).val();

    var pwStrength = $("#pw_strength");
    var score = calcScore(password1);
    pwStrength.text(getPassStrength(score));
    pwStrength.css("color", getStrengthColor(score));
    checkIfPasswordsMatch(password1, password2);
  });
});