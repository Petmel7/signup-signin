
function formatTime(sentAt) {
    const sentAtDate = new Date(sentAt);
    const hours = sentAtDate.getHours().toString().padStart(2, '0');;
    const minutes = sentAtDate.getMinutes().toString().padStart(2, '0');;
    const formattedTime = `${hours}:${minutes}`;
    return formattedTime;
}
