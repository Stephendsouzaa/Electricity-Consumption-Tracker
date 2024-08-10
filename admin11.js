function toggleLoginForm(showLoginForm) {
    var loginForm = document.getElementById("login-form");
    var signupForm = document.getElementById("signup-form");

    if (showLoginForm) {
        loginForm.style.display = "block";
        signupForm.style.display = "none";
    } else {
        loginForm.style.display = "none";
        signupForm.style.display = "block";
    }
}

function clearForm() {
    document.getElementById("consumer-id").value = "";
    document.getElementById("place").value = "";
    document.getElementById("consumption-date").value = "";
    document.getElementById("consumption-amount").value = "";
}

function logoutAdmin() {
    // Redirect to logout page or perform logout operation
    window.location.href = "logout.php"; // Assuming logout.php handles the logout process
}
