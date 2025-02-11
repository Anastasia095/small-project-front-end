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
    // Handle Signup Form Submission
    document.querySelector("#SignupModal form").addEventListener("submit", async function (event) {
        event.preventDefault();

        const signupEmail = document.getElementById("SignupModalEmail").value;
        const signupPassword = document.getElementById("SignupModalPassword").value;
        const errorDiv = document.getElementById("servererror");

        console.log("Signup Attempt:", { email: signupEmail, password: signupPassword });

        async function handleSignup() {
            try {
                const response = await fetch("/api/SignupController.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        email: signupEmail,
                        password: signupPassword,
                        FirstName: 'Test',
                        LastName: 'Test'
                    }),
                });

                const text = await response.text(); // Get response as text

                let data;
                try {
                    data = JSON.parse(text); // Try parsing JSON
                } catch {
                    data = { message: text }; // If parsing fails, treat as plain text
                }

                if (!response.ok) {
                    console.log("Server Error Response:", data);
                    errorDiv.textContent = data.message || `Error: ${response.statusText}`;
                    errorDiv.hidden = false; // Show error message
                    throw new Error(data.message || `Server responded with status ${response.status}`);
                }


                try {
                    const data = JSON.parse(text); // Try to parse the text as JSON
                    console.log("Server Response:", data);

                    if (data.success) {
                        window.location.href = '/contacts.php'; // Redirect on success
                    } else {
                        errorDiv.textContent = data.message; // Show error message
                        errorDiv.hidden = false; // Make error visible
                    }
                } catch (error) {
                    console.error("Failed to parse JSON:", error);
                    throw new Error("Invalid server response format.");
                }
            } catch (error) {
                console.error("Error:", error);
                errorDiv.textContent = error.message;
                errorDiv.hidden = false; // Show error message
            }
        }

        await handleSignup();
    });

});
