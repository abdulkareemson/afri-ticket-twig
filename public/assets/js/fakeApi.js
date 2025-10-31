// public/assets/js/fakeApi.js

const FakeApi = {
  delay(ms = 400) {
    return new Promise((res) => setTimeout(res, ms));
  },

  // USERS
  async signup({ name, email, password }) {
    await this.delay();

    if (!name || !email || !password)
      throw new Error("All fields are required.");

    const users = Storage.getUsers();
    if (users.some((u) => u.email.toLowerCase() === email.toLowerCase())) {
      throw new Error("Email already registered.");
    }

    const newUser = {
      id: crypto.randomUUID(),
      name,
      email,
      password,
      createdAt: new Date().toISOString(),
    };

    Storage.saveUser(newUser);
    Storage.setSession({ id: newUser.id, name, email });

    return { id: newUser.id, name, email };
  },

  async login({ email, password }) {
    await this.delay();

    const users = Storage.getUsers();
    const found = users.find(
      (u) =>
        u.email.toLowerCase() === email.toLowerCase() && u.password === password
    );

    if (!found) throw new Error("Invalid email or password.");

    Storage.setSession({ id: found.id, name: found.name, email: found.email });
    return found;
  },

  async logout() {
    Storage.clearSession();
  },

  // TICKETS
  async getTickets() {
    await this.delay();
    return Storage.getTickets();
  },

  async createTicket({
    title,
    description,
    priority = "medium",
    status = "open",
  }) {
    await this.delay();
    if (!title) throw new Error("Ticket title is required.");

    const newTicket = {
      id: crypto.randomUUID(),
      title,
      description,
      priority,
      status,
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
    };

    Storage.saveTicket(newTicket);
    return newTicket;
  },

  async updateTicket(id, updates) {
    await this.delay();
    Storage.updateTicket(id, updates);
    return Storage.getTicketById(id);
  },

  async deleteTicket(id) {
    await this.delay();
    Storage.deleteTicket(id);
    return true;
  },
};
