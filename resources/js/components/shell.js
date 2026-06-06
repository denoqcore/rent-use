
export default function shellComponent() {
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

        reviewModal: false,
        reviewRating: 0,
        reviewHover: 0,

        pendingBookings: 0,
        bookingsLoading: false,
        incomingRequests: [],
        myRentals: [],


        init() {
         this.initNotifications();
         this.loadPendingBookings();
        },

       initNotifications() {
    if (!window.Echo) {
        console.log('Echo not found');
        return;
    }

    const authId = document.querySelector('meta[name="auth-id"]')?.content;
    if (!authId) return;

    const channel = window.Echo.private(`user.${authId}`);

    console.log('Channel:', channel);

    channel.subscribed(() => {
        console.log('Successfully subscribed to user.' + authId);
    });

    channel.error((error) => {
        console.error('Channel error:', error);
    });

    channel.listen('MessageSent', (e) => {
        console.log('MessageSent received on user channel:', e);
        if (!this.chatsModal || this.activeChatId !== e.chat_id) {
            this.unreadTotal++;
            const chat = this.chats.find(c => c.id === e.chat_id);
            if (chat) {
                chat.last_message = {
                    body: e.body,
                    created_at: e.created_at,
                    is_mine: false,
                };
                chat.unread++;
            }
        }
    });

    channel.listen('BookingCreated', (e) => {
        this.pendingBookings++;
    });
},
        closeChats() {
            this.chatsModal = false;
            this.chatView = 'list';
        },

        async openChats() {
        this.chatsModal = true;
        this.chatView = 'list';
        this.chatsLoading = true;

        try {
            const res = await fetch('/chats', {
                headers: { Accept: 'application/json' }
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const data = await res.json();
            this.chats = Array.isArray(data) ? data : [];

        } catch (e) {
            console.error('openChats error:', e);
            this.chats = [];
        }

        this.unreadTotal = this.chats.reduce((sum, chat) => sum + chat.unread, 0);
        this.chatsLoading = false;
        await this.loadPendingBookings();
    },

        async openChat(chatId) {
        this.chatView = 'chat';
        this.activeChatId = chatId;
        this.activeMessages = [];

    const chat = this.chats.find(c => c.id === chatId);
    if (chat) {
        this.unreadTotal = Math.max(0, this.unreadTotal - chat.unread);
        chat.unread = 0;
    }

    try {
        const res = await fetch(`/chats/${chatId}`, {
            headers: { Accept: 'application/json' }
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        this.activeChatData = data.chat;
        this.activeMessages = Array.isArray(data.messages) ? data.messages : [];
    } catch (e) {
        console.error('openChat error:', e);
        this.activeMessages = [];
    }

    this.$nextTick(() => {
        const el = document.getElementById('chatScrollArea');
        if (el) el.scrollTop = el.scrollHeight;
    });

    if (window._echoChat) {
        window._echoChat.stopListening('MessageSent');
    }

    window._echoChat = window.Echo
        .private(`chat.${chatId}`)
    .listen('MessageSent', (e) => {
        const authId = document.querySelector('meta[name="auth-id"]')?.content;
        if (String(e.sender_id) === String(authId)) return;

        this.activeMessages.push({
            ...e,
            is_mine: false,
        });
        this.$nextTick(() => {
            const el = document.getElementById('chatScrollArea');
            if (el) el.scrollTop = el.scrollHeight;
            });
        });
    },



        async loadPendingBookings() {
            try {
                const res = await fetch('/api/bookings/pending-count', {
                    headers: { Accept: 'application/json' }
                });
                const data = await res.json();
                this.pendingBookings = data.count;
            } catch (e) {
                console.error('pendingBookings error:', e);
            }
        },

        async loadBookings() {
        this.bookingsLoading = true;
        try {
            const res = await fetch('/api/bookings', {
                headers: { Accept: 'application/json' }
            });
            const data = await res.json();
            this.incomingRequests = data.incoming;
            this.myRentals = data.rentals;
            this.pendingBookings = data.incoming.filter(b => b.status === 'pending').length;
        } catch (e) {
            console.error('loadBookings error:', e);
        }
        this.bookingsLoading = false;
    },

    async confirmBooking(id) {
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        await fetch(`/bookings/${id}/confirm`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' }
        });
        await this.loadBookings();
    },

    async cancelBooking(id) {
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        await fetch(`/bookings/${id}/cancel`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
                Accept: 'application/json'
            },
            body: JSON.stringify({ cancelled_by: 'owner' })
        });
        await this.loadBookings();
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

             this.activeMessages.push({
            id: Date.now(),
            body: body,
            is_mine: true,
            created_at: new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }),
            });

            this.$nextTick(() => {
                const el = document.getElementById('chatScrollArea');
                if (el) el.scrollTop = el.scrollHeight;
            });

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
        },

        async deleteBooking(id) {
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        await fetch(`/bookings/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrf,
                Accept: 'application/json'
            }
        });
        await this.loadBookings();
        },
    };


}

