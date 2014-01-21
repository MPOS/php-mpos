function scorePassword(pass) {
    var score = 0;
    if (!pass)
        return score;
    var letters = new Object();
    for (var i=0; i<pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
        spChars: /!@#\$%\^\&*\)\(+=._-/.test(pass),
    }
    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
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
	var score = scorePassword(pass)
	if (score >= 80)
		return "#390"
	if (score >= 70)
		return "#3C0"
	if (score >= 50)
		return "#399"
	if (score >= 40)
		return "#F60"
	if (score >= 10)
		return "#E00"
	if (score < 10)
		return "#C00"
	return "#999"
}
function checkIfPasswordsMatch() {
	var pwMatch = document.getElementById('pw_match');
	var field1 = document.getElementById('pw_field').value;
    var field2 = document.getElementById('pw_field2').value;
    if (field1 == field2 && field1 !== "" && field2 !== "") {
    	pwMatch.innerHTML = "Passwords match!";
    	pwMatch.style.color = "#390";
    } else if (field1 == "" || field2 == ""){
    	pwMatch.innerHTML = "";
    } else {
    	pwMatch.innerHTML = "Passwords don't match!";
    	pwMatch.style.color = "#399";
    }
}
$(document).ready(function() {
    $("#pw_field,#pw_field2").on("keypress keyup keydown", function() {
        var fieldValue = document.getElementById('pw_field').value;
        var pwStrength = document.getElementById('pw_strength');
        pwStrength.innerHTML = checkPassStrength(fieldValue);
        pwStrength.style.color = getStrengthColor(fieldValue);
        checkIfPasswordsMatch();
    });
});