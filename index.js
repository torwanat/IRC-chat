const messageInput = document.getElementById("message-input");
// const chatList = document.getElementById("chat-list");
let nick = "";
let lastId = "0";



messageInput.addEventListener("keydown", (event) => {
    if (event.key == "Enter" && messageInput.value != "") {
        sendMessage(messageInput.value);
        messageInput.value = "";
    }
});

const makeNick = (length) => {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }
    return result;
}

const login = () => {
    const myScrollbar = document.querySelector('.main-chat').fakeScroll({
        onChange: () => console.log("morbius:", myScrollbar.scrollRatio)
    })
    while (nick == "") {
        nick = prompt("Podaj nick:");
    }
    if (nick == null) {
        nick = makeNick(5);
    }
    console.log(nick);
    sendRequest();
}

const sendMessage = async (message) => {
    const jsonToSend = JSON.stringify({ message, nick });
    console.log(jsonToSend);
    const response = await fetch("./send.php", { method: "POST", body: jsonToSend });
    if (message[0] == "/") {
        if (message.split(" ")[0] == "/quit") {
            console.log("quit");
            location.reload();
        } else if (message.split(" ")[0] == "/nick" && message.split(" ").length > 1) {
            nick = message.split(" ")[1];
        }

    }
    const json = await response.text();
    console.log(json);
    return json;

}

const sendRequest = async () => {
    const body = JSON.stringify(lastId);
    console.log(body);
    const request = new Request("alp.php", {
        method: "POST",
        body: body,
        headers: {
            "Content-Type": "application/json"
        }
    });

    try {
        const response = await fetch(request);
        if (response.status !== 200) throw new Error("Something went wrong on API server!");
        const json = await response.json();
        console.log(json); //important won't work if deleted
        if (json.status != null) {
            lastId = json.currentId;
            if (json.message != "") {
                addMessage(json.message, json.nick, json.timestamp, json.color);
            }
        }
        sendRequest();
    } catch (error) {
        console.error(error);
    }

}

const addMessage = (message, name, timestamp, color) => {
    const messageLi = document.createElement("div");
    messageLi.className = "chat-message";
    messageLi.innerHTML = "[" + timestamp + "]" + "\&lt;<span style='color:" + color + "'>@" + name + "</span>\&gt;" + "<p class='chat-message-content'>" + message + "</p>";
    // chatList.appendChild(messageLi);
    document.querySelector(".fakeScroll__content").appendChild(messageLi);

    $(".chat-message-content").emoticonize();
}