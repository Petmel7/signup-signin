function getMessageStyles(isSender) {
    const messageClass = isSender ? 'message-sender' : 'message-recipient';
    const displayStyle = isSender ? 'none' : 'block';

    return {
        messageClass,
        displayStyle
    }
}

function calculateStyles(showAvatar, isSender, displayStyle) {

    const avatarDisplayStyle = showAvatar ? displayStyle : 'none';
    const marginLeftStyle = showAvatar ? '0px' : '50px';
    const borderStyle = showAvatar ? '0 10px 10px 10px' : '10px';
    const borderStyleSender = showAvatar ? '10px 0 10px 10px' : '10px';
    const dynamicBorderStyle = isSender ? borderStyleSender : borderStyle;

    return {
        avatarDisplayStyle,
        marginLeftStyle,
        borderStyle,
        dynamicBorderStyle
    };
}

function calculateStylesLocalStorage(isSender) {
    const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';

    const textColorClass = isDarkModeEnabled ? 'white-text' : '';
    const backgroundSenderClass = isDarkModeEnabled ? 'background-sender--class' : '';
    const backgroundClassMessages = isDarkModeEnabled ? 'background-messages' : '';
    const recipientWhiteText = isDarkModeEnabled ? 'recipient-white-text' : '';
    const messageDateStyle = isDarkModeEnabled ? 'message-date--style' : 'message-date';
    const messageDateStyleDisplay = isSender ? 'message-date' : messageDateStyle;
    const modalThemeStyle = isDarkModeEnabled ? 'modal-content--dark' : 'modal-content--white';

    return {
        textColorClass,
        backgroundSenderClass,
        backgroundClassMessages,
        recipientWhiteText,
        messageDateStyleDisplay,
        modalThemeStyle
    }
}