from flask import Flask
import json
from revChatGPT.revChatGPT import Chatbot

app = Flask(__name__)

try:
    with open("config.json", "r") as f:
        config = json.load(f)
except FileNotFoundError:
    print("Error: config file not found.")
    exit(1)

@app.route("/api/chat", methods=["POST"])
def api_chat():
    conversation_id = request.form.get("conversation_id")
    text = request.form.get("text")
    chatbot = Chatbot(config, conversation_id=conversation_id)
    message = chatbot.get_chat_response(text)
    return message

if __name__ == "__main__":
    app.run()
