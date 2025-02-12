const fetchContacts = async (userId) => {
    try {
        // GET request to the API endpoint with the user ID
        const response = await fetch(`/api/getAllContacts.php?user_id=${userId}`);

        // Parse the response as JSON
        const data = await response.json();

        // Check if response is good and not error
        if (!data.success) {
            // If not, throw an error with the message from the server
            throw new Error(data.message || "Unknown error");
        }

        // Return the list of contacts
        return data.contacts;
    } catch (error) {
        // Log any errors in the console
        console.error("Error fetching contacts:", error);

        throw error;
    }
};

// render the list of contacts
const renderContacts = (contacts) => {
    // Select table element where contacts will be displayed
    const tableBody = document.getElementById("contacts-table-body");

    // Select element for when no contacts are available
    const noContactsMessage = document.getElementById("no-contacts-message");

    // Check if there are no contacts
    if (contacts.length === 0) {
        // If no contacts, show noContactsMessage element
        noContactsMessage.classList.remove("d-none");
    } else {
        // if we have contacts hide noContactsMessage container
        noContactsMessage.classList.add("d-none");

        // Loop through contacts and create table rows
        contacts.forEach((contact, index) => {
            const row = document.createElement("tr");

            // inner HTML with contact details
            row.innerHTML = `
                <th scope="row">${index + 1}</th>
                <td>${contact.name}</td>
                <td>${contact.number}</td>
                <td>${contact.email}</td>
                <td><button type="button" class="btn btn-warning mx-1">✏ Update</button></td>
                <td><button type="button" class="btn btn-danger mx-1">🗑 Delete</button></td>
            `;

            // Append the row to the table
            tableBody.appendChild(row);
        });
    }
};

//load contacts on page load
const loadContacts = async () => {
    const userId = 1; // Placeholder for the user ID
    const noContactsMessage = document.getElementById("no-contacts-message");
    try {
        // Fetch the contacts
        const contacts = await fetchContacts(userId);

        // Render the contacts in the table
        renderContacts(contacts);
    } catch (error) {
        // If there's an error, display an error in the container
        noContactsMessage.textContent = "Failed to load contacts.";
        noContactsMessage.classList.remove("d-none");
    }
};

document.addEventListener("DOMContentLoaded", loadContacts);
