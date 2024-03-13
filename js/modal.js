
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

function openModalDelete(messageId, isSender) {
    console.log("messageId-->", messageId)
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `
        <button class="custom-delete--button" onclick="deleteMessage(${messageId}, event)">Delete</button>
        <button id="updateId" class="custom-delete--button" onclick="openUpdateFormAndCloseModal(${messageId})">Update</button>
        <button class="custom-delete--button" onclick="closeModal()">Cancel</button>
    `;

    const updateId = document.getElementById('updateId');

    isSender ? updateId.style.display = 'block' : updateId.style.display = 'none';
    console.log("isSender", isSender)
}

function openModalDeleteAllChat(messageUserId) {
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContentAllChat');
    modalContent.innerHTML = `
        <h3 class="delete-chat">Delete this chat?</h3>
        <button class="custom-delete--button" data-userid="${messageUserId}" onclick="deleteUserAllChat(${currentUserId}, ${messageUserId}, event)">Delete</button>
        <button class="custom-delete--button" onclick="closeModal()">Cancel</button>
    `;
}