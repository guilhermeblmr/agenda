<template>
    <div class="w-full flex justify-center pt-24">
        <div class="container gap-4 w-3/4">

            <div class="form-container bg-[#E6ECFF] w-3/4 p-4 rounded-b-lg flex gap-4 fixed z-1 top-0 drop-shadow-lg">
                <input v-model="form.name" placeholder="Nome"
                    class="w-1/5 p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">
                <input v-model="form.email" placeholder="E-mail"
                    class="w-1/5 p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">
                <input v-model="form.address" placeholder="Endereço"
                    class="w-1/5 p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">
                <input v-model="form.phone" placeholder="Telefone" type="number"
                    class="w-1/5 p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">

                <button @click="createContact()"
                    class="w-1/5 rounded-lg bg-[#5D86E0] transition hover:bg-[#5d70dd] text-white text-center font-bold">
                    Adicionar
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="contact in contacts" :key="contact.id" class="p-4 bg-[#F9F9F9] drop-shadow-lg
                    transition hover:shadow-md rounded-lg flex flex-col gap-2 justify-between">
                    <div class="">
                        <div class="flex flex-col gap-1">
                            <div class="font-bold">Nome:</div>
                            <div class="overflow-hidden text-ellipsis">{{ contact.name }}</div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <div class="font-bold">E-mail:</div>
                            <div class="overflow-hidden text-ellipsis">{{ contact.email }}</div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <div class="font-bold">Endereço:</div>
                            <div class="overflow-hidden text-ellipsis">{{ contact.address }}</div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <div class="font-bold">Telefone:</div>
                            <div class="overflow-hidden text-ellipsis">{{ contact.phone }}</div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4 gap-3">
                        <button @click="editContact(contact)"
                            class="bg-yellow-300  transition hover:bg-yellow-400 px-4 py-2 rounded-lg w-1/2">
                            Editar
                        </button>
                        <button @click="deleteContact(contact.id)"
                            class="bg-red-400 text-white transition hover:bg-red-500 px-4 py-2 rounded-lg w-1/2">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showEditModal"
                class="transition fixed inset-0 backdrop-blur-xs bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h2 class="text-xl font-bold mb-4">Editar Contato</h2>

                    <div class="flex flex-col gap-4">
                        <input v-model="selectedContact.name" placeholder="Nome"
                            class="p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">
                        <input v-model="selectedContact.email" placeholder="E-mail"
                            class="p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">
                        <input v-model="selectedContact.address" placeholder="Endereço"
                            class="p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">
                        <input v-model="selectedContact.phone" placeholder="Telefone"
                            class="p-2 border rounded-lg border-gray-200 bg-white focus:outline-2 focus:outline-[#5D86E0]">

                        <div class="flex justify-end gap-2">
                            <button @click="showEditModal = false"
                                class="px-4 py-2 transition bg-gray-300 hover:bg-gray-400 rounded-lg">
                                Cancelar
                            </button>
                            <button @click="updateContact()"
                                class="px-4 py-2 rounded-lg bg-[#5D86E0] transition hover:bg-[#5d70dd] text-white text-center font-bold">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showDeleteModal"
                class="fixed inset-0 backdrop-blur-xs bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h2 class="text-xl font-bold mb-4">Confirmar Exclusão</h2>
                    <p class="mb-6">Tem certeza que deseja excluir este contato?</p>

                    <div class="flex justify-end gap-2">
                        <button @click="showDeleteModal = false"
                            class="px-4 py-2 bg-gray-300 transition hover:bg-gray-400 rounded">
                            Cancelar
                        </button>
                        <button @click="confirmDelete()"
                            class="px-4 py-2 bg-red-400 transition hover:bg-red-500 text-white rounded">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const contacts = ref([]);
const form = reactive({ name: '', email: '', address: '', phone: '' });
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const contactToDeleteId = ref(null);
const selectedContact = reactive({
    id: null,
    name: '',
    email: '',
    address: '',
    phone: ''
});

const showToast = (message, type) => {
    toast(message, {
        type: type,
        position: 'bottom-right',
        autoClose: 3000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
    });
};

const getContacts = async () => {
    try {
        const response = await axios.get('/api/contacts');
        contacts.value = response.data;
    } catch (error) {
        showToast('Erro ao carregar contatos', 'error');
    }
};

const createContact = async () => {
    try {
        await axios.post('/api/contacts', form);
        resetForm();
        await getContacts();
        showToast('Contato criado com sucesso!', 'success');
    } catch (error) {
        Object.entries(error.response.data.errors).forEach(([key, value]) => {
            showToast(`${value[0]}`, 'error');
        });
    }
};

const editContact = (contact) => {
    Object.assign(selectedContact, contact);
    showEditModal.value = true;
};

const updateContact = async () => {
    try {
        await axios.put(`/api/contacts/${selectedContact.id}`, selectedContact);
        showEditModal.value = false;
        await getContacts();
        resetForm();
        showToast('Contato atualizado com sucesso!', 'success');
    } catch (error) {
        Object.entries(error.response.data.errors).forEach(([key, value]) => {
            showToast(`${value[0]}`, 'error');
        });
    }
};

const deleteContact = (id) => {
    contactToDeleteId.value = id;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    try {
        await axios.delete(`/api/contacts/${contactToDeleteId.value}`);
        showDeleteModal.value = false;
        await getContacts();
        showToast('Contato excluído com sucesso!', 'success');
    } catch (error) {
        showToast('Erro ao excluir contato!', 'error');
    }
};

const resetForm = () => {
    Object.assign(form, { name: '', email: '', address: '', phone: '' });
};

onMounted(() => {
    getContacts();
});
</script>
