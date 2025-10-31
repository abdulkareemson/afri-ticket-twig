// public/assets/js/storage.js

const Storage = {
  KEYS: {
    USERS: "users",
    TICKETS: "tickets",
    SESSION: "session",
  },

  _load(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : [];
  },

  _save(key, data) {
    localStorage.setItem(key, JSON.stringify(data));
  },

  _remove(key) {
    localStorage.removeItem(key);
  },

  // USERS
  getUsers() {
    return this._load(this.KEYS.USERS);
  },

  saveUser(user) {
    const users = this.getUsers();
    users.push(user);
    this._save(this.KEYS.USERS, users);
  },

  // TICKETS
  getTickets() {
    return this._load(this.KEYS.TICKETS);
  },

  saveTicket(ticket) {
    const tickets = this.getTickets();
    tickets.push(ticket);
    this._save(this.KEYS.TICKETS, tickets);
  },

  getTicketById(id) {
    return this.getTickets().find((t) => t.id === id) || null;
  },

  updateTicket(id, updates) {
    const tickets = this.getTickets();
    const updated = tickets.map((t) =>
      t.id === id
        ? { ...t, ...updates, updatedAt: new Date().toISOString() }
        : t
    );
    this._save(this.KEYS.TICKETS, updated);
  },

  deleteTicket(id) {
    const filtered = this.getTickets().filter((t) => t.id !== id);
    this._save(this.KEYS.TICKETS, filtered);
  },

  // SESSION
  getSession() {
    return JSON.parse(localStorage.getItem(this.KEYS.SESSION) || "null");
  },

  setSession(sessionObj) {
    localStorage.setItem(this.KEYS.SESSION, JSON.stringify(sessionObj));
  },

  clearSession() {
    this._remove(this.KEYS.SESSION);
  },
};
