
function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}

window.onclick = function (event) {
    let modal = document.getElementById('myModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

function openModalDelete(messageId) {
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `
        <button class="message-a__button" onclick="deleteMessage(${messageId}, event)">Delete</button>
        <button class="message-a__button" onclick="closeModal()">Cancel</button>
    `;
}

function openModalDeleteAllChat(messageUserId) {
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContentAllChat');
    modalContent.innerHTML = `
        <h3 class="delete-chat">Delete this chat?</h3>
        <button class="message-a__button" data-userid="${messageUserId}" onclick="deleteUserAllChat(${currentUserId}, ${messageUserId}, event)">Delete</button>
        <button class="message-a__button" onclick="closeModal()">Cancel</button>
    `;
}