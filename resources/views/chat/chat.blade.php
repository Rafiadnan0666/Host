@extends('layout.dash')

@section('konten')
<div class="h-100">
    <div class="row">
        <!-- Contact List -->
        <div class="col-md-4 border-end">
            <h4>Contacts</h4>
            <input type="text" id="searchUser" class="form-control" placeholder="Search user...">
            <ul class="list-group mt-2" id="contactList">
                @foreach($contacts as $contact)
                    <li class="list-group-item contact d-flex align-items-center gap-3" data-id="{{ $contact->id }}">
                        <img src="{{ asset('storage/' . $contact->gambar) }}" alt="{{ $contact->name }}"
                             class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        <span>{{ $contact->name }}</span>
                    </li>
                @endforeach
            </ul>
            
        </div>

        <!-- Chat Window -->
        <div class="col-md-8">
            <h4 id="chatTitle">Select a contact to start chatting</h4>
            <div id="chatBox" class="chat-box border p-3" style="height: 400px; overflow-y: scroll; display: none;">
                <!-- Messages will be loaded here -->
            </div>

           <!-- Message Input -->
<form id="chatForm" style="display: none;">
    @csrf
    <input type="hidden" id="toUserId" name="to_user_id">
    <div class="input-group mt-3">
        <input type="text" id="messageInput" name="message" class="form-control" placeholder="Type a message..." required>
        <button type="submit" class="btn btn-primary">Send</button>
    </div>
</form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(chatForm);

            fetch(chatForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                // Optionally: Handle the response, update chat, etc.
                messageInput.value = ''; // Clear input after send
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.getElementById('chatBox');
    const chatForm = document.getElementById('chatForm');
    const chatTitle = document.getElementById('chatTitle');
    const messageInput = document.getElementById('messageInput');
    const contactList = document.getElementById('contactList');
    let currentUserName = ''; 
    let currentUserId = null; // Store the current chat user ID

    function appendMessage(msg, userName) {
        let align = msg.from_user_id == {{ auth()->id() }} ? 'text-end' : 'text-start';
        let sender = msg.from_user_id == {{ auth()->id() }} ? 'You' : userName;
        chatBox.innerHTML += `<div class="${align}">
            <strong>${sender}</strong>
            <p class="bg-light p-2 rounded">${msg.message}</p>
        </div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function loadChat(userId, userName) {
        currentUserId = userId; 
        currentUserName = userName;
        chatBox.innerHTML = '<p>Loading messages...</p>';
        chatBox.style.display = 'block';
        chatForm.style.display = 'block';
        chatTitle.innerHTML = `Chat with ${userName}`;
        document.getElementById('toUserId').value = userId;

        fetch(`/chat/messages/${userId}`)
            .then(response => response.json())
            .then(messages => {
                chatBox.innerHTML = '';
                messages.forEach(msg => appendMessage(msg, userName));
            });
    }

    contactList.addEventListener('click', function (e) {
        if (e.target.classList.contains('contact')) {
            loadChat(e.target.dataset.id, e.target.textContent);
        }
    });

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const toUserId = document.getElementById('toUserId').value;
        const message = messageInput.value;

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('#chatForm input[name=_token]').value
            },
            body: JSON.stringify({ to_user_id: toUserId, message: message })
        }).then(response => response.json())
        .then(newMessage => {
            messageInput.value = '';
            appendMessage(newMessage, currentUserName);
        }).catch(error => console.error('Error:', error));
    });

    document.getElementById('searchUser').addEventListener('input', function () {
        fetch("/chat/search?search=" + this.value)
            .then(response => response.json())
            .then(users => {
                let list = '';
                users.forEach(user => {
                    list += `<li class="list-group-item contact" data-id="${user.id}">${user.name}</li>`;
                });
                contactList.innerHTML = list;
            });
    });

    function refreshChat() {
        if (currentUserId) {
            fetch(`/chat/messages/${currentUserId}`)
                .then(response => response.json())
                .then(messages => {
                    chatBox.innerHTML = '';
                    messages.forEach(msg => appendMessage(msg, currentUserName));
                });
        }
    }

    setInterval(refreshChat, 2500); 
});
</script>
@endsection
