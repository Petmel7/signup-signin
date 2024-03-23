
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

const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
const textColorClass = isDarkModeEnabled ? 'white-text' : '';

function openModalDelete(messageId, isSender) {
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContent');

    modalContent.innerHTML = `
        <button class="custom-delete--button ${textColorClass}" onclick="deleteMessage(${messageId}, event)">Delete</button>
        <button id="updateId" class="custom-delete--button ${textColorClass}" onclick="openUpdateFormAndCloseModal(${messageId})">Update</button>
        <button class="custom-delete--button ${textColorClass}" onclick="closeModal()">Cancel</button>
    `;

    const updateId = document.getElementById('updateId');
    isSender ? updateId.style.display = 'block' : updateId.style.display = 'none';
}

function openModalDeleteAllChat(messageUserId) {
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContentAllChat');

    modalContent.innerHTML = `
        <h3 class="delete-chat ${textColorClass}">Delete this chat?</h3>
        <button class="custom-delete--button ${textColorClass}" data-userid="${messageUserId}" onclick="deleteUserAllChat(${currentUserId}, ${messageUserId}, event)">Delete</button>
        <button class="custom-delete--button ${textColorClass}" onclick="closeModal()">Cancel</button>
    `;
}