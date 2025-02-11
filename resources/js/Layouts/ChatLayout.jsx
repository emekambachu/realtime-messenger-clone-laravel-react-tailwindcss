import {usePage} from "@inertiajs/react";

const ChatLayout = ({ children }) => {
    const page = usePage();
    const conversations = page.props.conversations;
    const selectedConversation = page.props.selectedConversation;

    console.log(conversations);
    console.log(selectedConversation);

    return (
        <div>
            Chat Layout
            <div>{children}</div>
        </div>
    )
}

export default ChatLayout;
