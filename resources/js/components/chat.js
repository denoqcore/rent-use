
export default function chatComponent() {
    return {
        open: false,
        scrolled: false,
        userMenu: false,
        logoutModal: false,
        favoritesModal: false,
        bookingsModal: false,
        bookingsTab: 'renter',

        chatsModal: false,
        chatView: 'list',

        chats: [],
        chatsLoading: false,

        activeChatId: null,
        activeChatData: null,
        activeMessages: [],

        chatInput: '',
        unreadTotal: 0,

        async openChats() {
            this.chatsModal = true;
            this.chatView = 'list';
            this.chatsLoading = true;

            const res = await fetch('/chats', {
                headers: {
                    Accept: 'application/json'
                }
            });

            this.chats = await res.json();

            this.unreadTotal = this.chats.reduce(
                (sum, chat) => sum + chat.unread,
                0
            );

            this.chatsLoading = false;
        },

        async openChat(chatId) {
            this.chatView = 'chat';
            this.activeChatId = chatId;
            this.activeMessages = [];

            const res = await fetch(`/chats/${chatId}`, {
                headers: {
                    Accept: 'application/json'
                }
            });

            const data = await res.json();

            this.activeChatData = data.chat;
            this.activeMessages = data.messages;

            this.$nextTick(() => {
                const el = document.getElementById('chatScrollArea');

                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            });

            if (window._echoChat) {
                window._echoChat.stopListening('.MessageSent');
            }

            window._echoChat = window.Echo
                .private(`chat.${chatId}`)
                .listen('.MessageSent', (e) => {
                    this.activeMessages.push(e);

                    this.$nextTick(() => {
                        const el = document.getElementById('chatScrollArea');

                        if (el) {
                            el.scrollTop = el.scrollHeight;
                        }
                    });
                });
        },

        async sendFirstMessage(listingId) {
            const textarea = document.getElementById('contactMessage');
            const btn      = document.getElementById('contactSendBtn');
            const success  = document.getElementById('contactSuccess');
            const error    = document.getElementById('contactError');
            const body     = textarea?.value.trim();

            if (!body) {
                if (textarea) {
                    textarea.style.borderColor = '#dc2626';
                    setTimeout(() => textarea.style.borderColor = 'var(--background-3)', 1500);
                }
                return;
            }

            btn.disabled        = true;
            btn.textContent     = 'Отправляем...';
            error.classList.add('hidden');

            try {
                const chatRes  = await fetch(`/chat/${listingId}`, {
                    headers: { Accept: 'application/json' }
                });
                const chatData = await chatRes.json();

                await fetch(`/chat/${chatData.chat.id}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ body })
                });

                textarea.value        = '';
                textarea.style.display = 'none';
                btn.style.display      = 'none';
                success.classList.remove('hidden');

            } catch (e) {
                error.textContent = 'Error, try again';
                error.classList.remove('hidden');
                btn.disabled    = false;
                btn.textContent = 'Send';
            }
        },

        async sendChatMessage() {
            const body = this.chatInput.trim();

            if (!body || !this.activeChatId) {
                return;
            }

            this.chatInput = '';

            await fetch(`/chat/${this.activeChatId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name=csrf-token]')
                        .content
                },
                body: JSON.stringify({ body })
            });
        }
    };
}
