
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
        editingMessageId: null,
        editingBody: '',
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
        const res = await fetch('/chats', { headers: { Accept: 'application/json' } });
        const data = await res.json();
        this.chats = Array.isArray(data) ? data : [];
    } catch (e) {
        this.chats = [];
    }

    this.unreadTotal = this.chats.reduce((sum, c) => sum + c.unread, 0);
    this.chatsLoading = false;
    await this.loadPendingBookings();
},

async _markRead(chatId) {
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    try {
        await fetch(`/chats/${chatId}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
        });
    } catch (e) {
        console.error('markRead error:', e);
    }
},

async openChat(chatId) {
    this.activeChatId = chatId;
    this.activeMessages = [];
    this.editingMessageId = null;
    this.chatView = 'chat'; // мобиль

    const chat = this.chats.find(c => c.id === chatId);
    if (chat) {
        this.unreadTotal = Math.max(0, this.unreadTotal - chat.unread);
        chat.unread = 0;
    }

    try {
        const res = await fetch(`/chats/${chatId}`, { headers: { Accept: 'application/json' } });
        const data = await res.json();
        this.activeChatData = data.chat;
        this.activeMessages = Array.isArray(data.messages) ? data.messages : [];
    } catch (e) {
        this.activeMessages = [];
    }

    this.$nextTick(() => this._scrollChat());

    if (window._echoChat) {
        window.Echo.leave(`chat.${window._echoChat}`);
    }
    window._echoChat = chatId;

    const authId = document.querySelector('meta[name="auth-id"]')?.content;

    window.Echo.private(`chat.${chatId}`)
        .listen('MessageSent', (e) => {
            if (String(e.sender_id) === String(authId)) return;
            this.activeMessages.push({ ...e, is_mine: false });
            this.$nextTick(() => this._scrollChat());
            this._markRead(chatId);
        })
        .listen('MessageRead', (e) => {
            if (String(e.reader_id) === String(authId)) return;
            this.activeMessages.forEach(m => {
                if (m.is_mine) m.read_at = true;
            });
        })
        .listen('MessageEdited', (e) => {
            const msg = this.activeMessages.find(m => m.id === e.id);
            if (msg) {
                msg.body = e.body;
                msg.edited_at = e.edited_at;
            }
        })
        .listen('MessageDeleted', (e) => {
            const msg = this.activeMessages.find(m => m.id === e.id);
            if (msg) {
                msg.is_deleted = true;
                msg.body = null;
            }
        });
},

_scrollChat() {
    const el = document.getElementById('chatScrollArea');
    if (el) el.scrollTop = el.scrollHeight;
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

async sendFirstMessage(listingId, context = 'default') {
    const textareaId = context === 'sheet' ? 'contactMessageSheet' : 'contactMessage';
    const btnId      = context === 'sheet' ? 'contactSendBtnSheet' : 'contactSendBtn';
    const successId  = context === 'sheet' ? 'contactSuccessSheet' : 'contactSuccess';
    const errorId    = context === 'sheet' ? 'contactErrorSheet'   : 'contactError';

    const textarea = document.getElementById(textareaId);
    const btn      = document.getElementById(btnId);
    const success  = document.getElementById(successId);
    const error    = document.getElementById(errorId);

    const body = textarea?.value?.trim();
    if (!body) {
        if (textarea) {
            textarea.style.borderColor = '#dc2626';
            setTimeout(() => textarea.style.borderColor = 'var(--background-3)', 1500);
        }
        return;
    }

    if (btn) { btn.disabled = true; btn.textContent = 'Sending...'; }
    if (error) error.classList.add('hidden');

    try {
        const chatRes  = await fetch(`/chat/listing/${listingId}`, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
        });
        const chatData = await chatRes.json();

        await fetch(`/chat/${chatData.chat.id}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({ body, listing_id: listingId }),
        });

        if (textarea) textarea.value = '';
        if (success) success.classList.remove('hidden');

        if (context !== 'sheet') {
            if (textarea) textarea.style.display = 'none';
            if (btn) btn.style.display = 'none';
        } else {
            setTimeout(() => { if (success) success.classList.add('hidden'); }, 1500);
        }
    } catch (e) {
        if (error) { error.textContent = 'Error, try again'; error.classList.remove('hidden'); }
        if (btn) { btn.disabled = false; btn.textContent = 'Send'; }
    }
},

async sendChatMessage() {
    const body = this.editingMessageId ? this.editingBody.trim() : this.chatInput.trim();
    if (!body || !this.activeChatId) return;

    if (this.editingMessageId) {
        await this._submitEdit(this.editingMessageId, body);
        return;
    }

    this.chatInput = '';

    const tempId = Date.now();
    const tempMsg = {
        id: tempId,
        body,
        is_mine: true,
        read_at: null,
        edited_at: null,
        listing: null,
        created_at: new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }),
    };
    this.activeMessages.push(tempMsg);
    this.$nextTick(() => this._scrollChat());

    const csrf = document.querySelector('meta[name=csrf-token]').content;
    try {
        const res = await fetch(`/chat/${this.activeChatId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ body }),
        });
        const data = await res.json();
        if (data.message) {
            tempMsg.id = data.message.id;
        }
    } catch (e) {
        console.error('sendChatMessage error:', e);
    }
},

startEdit(msg) {
    this.editingMessageId = msg.id;
    this.editingBody = msg.body;
    this.$nextTick(() => {
        const el = document.getElementById('chatEditInput');
        if (el) el.focus();
    });
},

cancelEdit() {
    this.editingMessageId = null;
    this.editingBody = '';
},

async _submitEdit(id, body) {
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch(`/chat/message/${id}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ body }),
    });
    const msg = this.activeMessages.find(m => m.id === id);
    if (msg) { msg.body = body; msg.edited_at = true; }
    this.editingMessageId = null;
    this.editingBody = '';
},

async deleteMessage(id) {
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch(`/chat/message/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' }
    });
    const msg = this.activeMessages.find(m => m.id === id);
    if (msg) {
        msg.is_deleted = true;
        msg.body = null;
    }
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

    }
}

