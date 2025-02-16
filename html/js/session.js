// Saving the userId in localStorage for upcoming requests

const localStorageSessionKey = "session";

export function sessionLogin(userId) {
    if (!userId) return;

    const newSession = {
        "userId": userId
    }

    localStorage.setItem(localStorageSessionKey, JSON.stringify(newSession));
    console.log("Logged in!");
    console.log(retrieveSession());
}

export function sessionLogout() {
    localStorage.removeItem(localStorageSessionKey);
}

export function retrieveSession() {
    const session = localStorage.getItem(localStorageSessionKey);
    if (!session) return null;
    return JSON.parse(session);
}

export function isLoggedIn() {
    return retrieveSession() != null;
}
