import './bootstrap';

import { createApp } from 'vue'

import LoginComponent from './vue/Login.vue';
const loginApp = createApp(LoginComponent);
loginApp.mount('#login');

import RegisterComponent from './vue/Register.vue';
const registerApp = createApp(RegisterComponent);
registerApp.mount('#register');

import NewsComponent from './vue/News.vue';
const newsApp = createApp(NewsComponent);
newsApp.mount('#news');
