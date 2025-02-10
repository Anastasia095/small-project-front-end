document.addEventListener("DOMContentLoaded", function () {
    // Handle Login Form Submission
    document.querySelector("#exampleModal form").addEventListener("submit", function (event) {
        event.preventDefault();
        const loginEmail = document.getElementById("exampleInputEmail1").value;
        const loginPassword = document.getElementById("exampleInputPassword1").value;

        console.log("Login Attempt:", { email: loginEmail, password: loginPassword });

        fetch("/api/LoginController.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email: loginEmail, password: loginPassword }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status ${response.status}`);
                }
                return response.text(); // Get response as text
            })
            .then(text => {
                try {
                    const data = JSON.parse(text); // Try to parse the text as JSON
                    console.log("Server Response:", data);

                    if (data.success) {
                        window.location.href = '/contacts.php'; // Redirect on success
                    } else {
                        alert(data.message); // Show error message if login fails
                    }
                } catch (error) {
                    console.error("Failed to parse JSON:", error);
                    alert("An error occurred. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            });

    });

    // Handle Signup Form Submission
    document.querySelector("#SignupModal form").addEventListener("submit", function (event) {
        event.preventDefault();

        const signupEmail = document.getElementById("SignupModalEmail").value;
        const signupPassword = document.getElementById("SignupModalPassword").value;

        console.log("Signup Attempt:", { email: signupEmail, password: signupPassword });

        fetch("/api/SignupController.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email: signupEmail, password: signupPassword }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status ${response.status}`);
                }
                return response.text(); // Get response as text
            })
            .then(text => {
                try {
                    const data = JSON.parse(text); // Try to parse the text as JSON
                    console.log("Server Response:", data);
                } catch (error) {
                    console.error("Failed to parse JSON:", error);
                }
            })
            .catch(error => console.error("Error:", error));
    });
});
