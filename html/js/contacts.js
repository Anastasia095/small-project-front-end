import { retrieveSession } from './session.js';


// API Calls
const fetchContacts = async (userId) => {
    try {
        const response = await fetch(`/api/getAllContacts.php?user_id=${userId}`);
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || "Unknown error");
        }

        return data.contacts;
    } catch (error) {
        console.error("Error fetching contacts:", error);
        throw error;
    }
};

const addContact = async (fname, lname, phone, email) => {
    const session = retrieveSession();
    if (!session || !session.userId) {
        window.location.href = "/";
        return;
    }

    const userId = session.userId;
    console.log(userId);
    const response = await fetch("/api/addContact.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ fname, lname, phone, email, userId }),
    });

    if (!response.ok) {
        throw new Error(`Server responded with status ${response.status}`);
    }

    const text = await response.text();
    let data;

    try {
        data = JSON.parse(text);
    } catch {
        throw new Error("Failed to parse JSON");
    }

    return data;
};

const editContact = async (id, fname, lname, phone, email) => {
    console.log(id, fname, lname, phone, email);
    const response = await fetch("/api/editContact.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, fname, lname, phone, email }),
    });

    if (!response.ok) {
        throw new Error(`Server responded with status ${response.status}`);
    }

    const text = await response.text();
    let data;

    try {
        data = JSON.parse(text);
    } catch {
        throw new Error("Failed to parse JSON");
    }

    return data;
};

// UI Updates
const redirectToContacts = () => {
    window.location.href = '/contacts.html';
};

const renderContacts = (contacts) => {
    const tableBody = document.getElementById("contacts-table-body");
    const noContactsMessage = document.getElementById("no-contacts-message");

    // Clear the table before rendering new contacts
    tableBody.innerHTML = '';

    //hide/add container with " No Contacts Message"
    if (contacts.length === 0) {
        noContactsMessage.classList.remove("d-none");
    } else {
        noContactsMessage.classList.add("d-none");

        //loop over contacts
        contacts.forEach((contact, index) => {
            const row = document.createElement("tr");

            //that speak for itself
            row.innerHTML = `
                <th scope="row">${index + 1}</th>
               <td>${contact.fname} ${contact.lname}</td>
                <td><a href="tel:${contact.number}">${contact.number} </a></td>
                <td><a href="mailto:${contact.email}">${contact.email}</a></td>
                <td>
                    <button type="button" class="button-update" data-bs-toggle="modal"
                    data-bs-target="#editModal"
                    data-bs-toggle="modal" 
                    data-bs-target="#editModal"
                    data-id="${contact.id}"
                    data-firstname="${contact.fname}"
                    data-lastname="${contact.lname}"
                    data-phone="${contact.number}"
                    data-email="${contact.email}">
                        <img src="https://img.icons8.com/?size=100&id=12133&format=png&color=000000" alt="Update" style="width: 25px; height: 25px; margin-right: 5px;">
                        
                    </button>
                </td>
                <td>
                    <button type="button" class="button-delete">
                        <img src="https://img.icons8.com/?size=100&id=u3z0y0I7ZJsN&format=png&color=000000" alt="Update" style="width: 25px; height: 25px; margin-right: 5px;">
                        
                    </button>
                </td>
            `;
            //inject generated html to the table element
            tableBody.appendChild(row);
        });
    }
};

//handle errors, that needs to be tweaked I think maybe not not sure
const showErrorMessage = (message, elementId) => {
    const errorDiv = document.getElementById(elementId);
    errorDiv.textContent = message;
    errorDiv.classList.remove("d-none");
};


// Event Handlers
const loadContacts = async () => {
    const session = retrieveSession();
    if (!session || !session.userId) {
        window.location.href = "/";
        return;
    }

    const userId = session.userId;

    const noContactsMessage = document.getElementById("no-contacts-message");

    try {
        const contacts = await fetchContacts(userId);
        renderContacts(contacts);
    } catch (error) {
        showErrorMessage("Failed to load contacts.", "no-contacts-message");
    }
};


const handleAddContact = async (event) => {
    event.preventDefault();

    const fname = document.getElementById("first-name").value;
    const lname = document.getElementById("last-name").value;
    const phone = document.getElementById("phone-number").value;
    const email = document.getElementById("email").value;

    console.log("Add Contact Attempt:", { fname: fname, lname: lname, phone: phone, email: email });

    try {
        const data = await addContact(fname, lname, phone, email);

        if (data.success) {
            redirectToContacts();
        } else {
            alert(data.message); // todo
        }
    } catch (error) {
        console.error("Update Contact Error:", error);
        alert("An error occurred. Please try again.");
    }
};

const handleEditContact = async (event) => {
    event.preventDefault();

    const id = document.getElementById("editContactId").value;
    const fname = document.getElementById("editFirstName").value;
    const lname = document.getElementById("editLastName").value;
    const phone = document.getElementById("editPhone").value;
    const email = document.getElementById("editEmail").value;

    try {
        const data = await editContact(id, fname, lname, phone, email);

        if (data.success) {
            // redirectToContacts();
            console.log("success");
        } else {
            alert(data.message); // todo
        }
    } catch (error) {
        console.error("Update Contact Error:", error);
        alert("An error occurred. Please try again.");
    }
};

// Event Listeners
const initializeEventListeners = () => {
    document.addEventListener("DOMContentLoaded", loadContacts);
    document.querySelector("#addModal form").addEventListener("submit", handleAddContact);
    document.querySelector("#editModal form").addEventListener("submit", handleEditContact);

    // Attach event listeners to update buttons
    document.addEventListener('click', function (event) {
        if (event.target.closest('.button-update')) {
            const button = event.target.closest('.button-update');

            const id = button.getAttribute('data-id');
            const fname = button.getAttribute('data-firstname');
            const lname = button.getAttribute('data-lastname');
            const phone = button.getAttribute('data-phone');
            const email = button.getAttribute('data-email');

            // prepopulate modal
            document.getElementById('editContactId').value = id;
            document.getElementById('editFirstName').value = fname;
            document.getElementById('editLastName').value = lname;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editEmail').value = email;
        }
    });
};

// Initialize all event listeners
initializeEventListeners();
