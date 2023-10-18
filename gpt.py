import json
import openai
import memcache
import readline
from datetime import datetime

# Задайте константы и параметры
PMC_USER_AI_KEY = 'pmc_user_ai_'
PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_'
USER_ID = 198239789

# Создайте объект Memcached
pmc = memcache.Client(['127.0.0.1:11209'])

# Функция для получения контекста из Memcached
def get_context(user_id):
    context = pmc.get(get_context_key(user_id))
    return context.decode('utf-8') if context else ""

# Функция для получения истории сообщений из Memcached
def get_history(user_id):
    history_json = pmc.get(get_history_key(user_id))
    return json.loads(history_json.decode('utf-8')) if history_json else []

# Функция для сохранения истории сообщений в Memcached
def set_history(history, user_id):
    history_json = json.dumps(history)
    pmc.set(get_history_key(user_id), history_json)

# Функция для генерации ответа с использованием OpenAI
def generate_response(input_text, context, history, model="gpt-4"):
    current_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    context += f". Текущая дата: {current_date}"
    openai.api_key = 'YOUR_OPENAI_API_KEY'  # Замените на свой API-ключ от OpenAI

    messages = [{"role": "system", "content": context}]
    for message in history:
        messages.append({"role": message["role"], "content": message["content"]})

    messages.append({"role": "user", "content": input_text})

    response = openai.ChatCompletion.create(
        model=model,
        messages=messages,
        max_tokens=500
    )

    answer = response['choices'][0]['message']['content']
    return answer

# Функция для создания ключа контекста
def get_context_key(user_id):
    return f"{PMC_USER_AI_KEY}tg{user_id}"

# Функция для создания ключа истории сообщений
def get_history_key(user_id):
    return f"{PMC_USER_AI_HISTORY_KEY}tg{user_id}"

# Получите контекст и историю сообщений из Memcached
context = get_context(USER_ID)
history = get_history(USER_ID)

# Получите ввод пользователя
user_input = input("Enter input: ")

# Генерируйте ответ и обновите историю
response = generate_response(user_input, context, history, "gpt-4")
history.append({"role": "user", "content": user_input})
history.append({"role": "assistant", "content": response})

# Сохраните обновленную историю в Memcached
set_history(history, USER_ID)

# Выведите ответ
print(response)
