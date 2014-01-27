function scorePassword(pass) {
  var score = 0;
  if (!pass)
    return score;
  var letters = new Object();
  for (var i = 0; i < pass.length; i++) {
    letters[pass[i]] = (letters[pass[i]] || 0) + 1;
    score += 5.0 / letters[pass[i]];
  }
  var variations = {
    digits: /\d/.test(pass),
    lower: /[a-z]/.test(pass),
    upper: /[A-Z]/.test(pass),
    nonWords: /\W/.test(pass),
    spChars: /!@#\$%\^\&*\)\(+=._-/.test(pass)
  };
  variationCount = 0;
  for (var check in variations) {
    variationCount += (variations[check] === true) ? 1 : 0;
  }
  score += (variationCount - 1) * 10;
  return parseInt(score);
}
function checkPassStrength(pass) {
  var score = scorePassword(pass);
  if (score >= 80)
    return "Excellent";
  if (score >= 70)
    return "Strong";
  if (score >= 50)
    return "Good";
  if (score >= 40)
    return "Weak";
  if (score >= 10)
    return "Very weak";
  if (score < 10 && score > 1)
    return "Extremely weak";
  return "";
}
function getStrengthColor(pass) {
  var score = scorePassword(pass);
  if (score >= 80)
    return "#390";
  if (score >= 70)
    return "#3C0";
  if (score >= 50)
    return "#399";
  if (score >= 40)
    return "#F60";
  if (score >= 10)
    return "#E00";
  if (score < 10)
    return "#C00";
  return "#999";
}
function checkIfPasswordsMatch(pwField1, pwField2) {
  var pwMatch = $("#pw_match");
  if ($(pwField1).val() === $(pwField2).val() && $(pwField1).val() !== "" && $(pwField2).val() !== "") {
    $(pwMatch).text("Passwords match!");
    $(pwMatch).css("color", "#390");
  } else if ($(pwField1).val() === "" || $(pwField2).val() === "") {
    $(pwMatch).text("");
  } else {
    $(pwMatch).text("Passwords don't match!");
    $(pwMatch).css("color", "#399");
  }
}
$(document).ready(function() {
  var pwField1 = $("#pw_field");
  var pwField2 = $("#pw_field2");

  $(pwField1).add(pwField2).on("keypress keyup keydown", function() {
    var fieldValue = $(pwField1).val();
    var pwStrength = $("#pw_strength");
    pwStrength.text(checkPassStrength(fieldValue));
    pwStrength.css("color", getStrengthColor(fieldValue));
    checkIfPasswordsMatch(pwField1, pwField2);
  });
});