function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    return pattern.test(emailAddress);
};

function isValidPassword(password) {
    var pattern = new RegExp(/^[a-z0-9_-]{6,15}$/);
    return pattern.test(password);
};
