<template>
    <div class="w-full flex justify-center">
        <div class="container flex flex-col w-3/4 gap-4">

          <h1 class="text-3xl text-white">Agenda de Contatos</h1>

          <!-- Formulário -->
          <div class="form-container bg-[#42b883] p-4 rounded-lg flex gap-4">
            <input v-model="form.name" placeholder="Nome" class="w-1/5 p-2 border rounded lg border-white">
            <input v-model="form.email" placeholder="E-mail" class="w-1/5 p-2 border rounded lg border-white">
            <input v-model="form.address" placeholder="Endereço" class="w-1/5 p-2 border rounded lg border-white">
            <input v-model="form.phone" placeholder="Telefone" class="w-1/5 p-2 border rounded lg border-white">

            <button @click="editMode ? updateContact() : createContact()" class="w-1/5">
              {{ editMode ? 'Atualizar' : 'Adicionar' }}
            </button>
          </div>

          <!-- Lista de Contatos -->
          <table class="w-full flex gap-4 flex-col">
            <tr v-for="contact in contacts" :key="contact.id" class="bg-[#42b883] flex p-4 gap-4 rounded-lg">
              <td class="w-1/5 overflow-hidden text-ellipsis transition hover:bg-[#75e6b2] p-2 rounded-lg">{{ contact.name }}</td>
              <td class="w-1/5 overflow-hidden text-ellipsis transition hover:bg-[#75e6b2] p-2 rounded-lg">{{ contact.email }}</td>
              <td class="w-1/5 overflow-hidden text-ellipsis transition hover:bg-[#75e6b2] p-2 rounded-lg">{{ contact.address }}</td>
              <td class="w-1/5 overflow-hidden text-ellipsis transition hover:bg-[#75e6b2] p-2 rounded-lg">{{ contact.phone }}</td>
              <td class="w-1/5 overflow-hidden text-ellipsi flex justify-between">
                <button @click="editContact(contact)" class="transition hover:bg-yellow-400 p-2 rounded-lg">Editar</button>
                <button @click="deleteContact(contact.id)" class="transition hover:bg-red-400 p-2 rounded-lg">Excluir</button>
              </td>
            </tr>
          </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      contacts: [],
      form: { name: '', email: '', address: '', phone: '' },
      editMode: false,
      editId: null
    }
  },
  mounted() {
    this.getContacts();
  },
  methods: {
    async getContacts() {
      const response = await axios.get('/api/contacts');
      this.contacts = response.data;
    },
    async createContact() {
      await axios.post('/api/contacts', this.form);
      this.resetForm();
      this.getContacts();
    },
    editContact(contact) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      this.form = { ...contact };
      this.editMode = true;
      this.editId = contact.id;
    },
    async updateContact() {
      await axios.put(`/api/contacts/${this.editId}`, this.form);
      this.resetForm();
      this.getContacts();
      this.editMode = false;
    },
    async deleteContact(id) {
      await axios.delete(`/api/contacts/${id}`);
      this.getContacts();
    },
    resetForm() {
      this.form = { name: '', email: '', address: '', phone: '' };
    }
  }
}
</script>
