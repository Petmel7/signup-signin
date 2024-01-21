function searchFriends() {
    // Отримати елемент форми
    let form = document.getElementById('friendsForm');

    // Здійснити відправку форми
    form.submit();

    // Перейти на іншу сторінку (зараз відбудеться після відправки форми)
    window.location.href = 'index.php?page=friends-list';
}