function forwarding() {
    window.location.href = 'index.php?page=friends-list';
}

function redirectToMyFriends() {
    window.location.href = 'index.php?page=my-friends-list';
}

function redirectToMySubscribers() {
    window.location.href = 'index.php?page=my-subscribers-list';
}

function redirectionHisFriends(username) {
    window.location.href = `index.php?page=his-friends-list&username=${encodeURIComponent(username)}`;
}

function redirectionHisSubscribers(username) {
    window.location.href = `index.php?page=his-subscribers-list&username=${encodeURIComponent(username)}`;
}
