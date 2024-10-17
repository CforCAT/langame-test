<template>
    <div class="wrapper">
        <div v-if="step === 1">
            <form>
                <div class="iw">
                    <label for="name">Name
                        <input id="name" type="text" v-model="name"/>
                    </label>
                    <div class="error" v-if="errors.name">{{ errors.name }}</div>
                </div>
                <div class="iw">
                    <label for="phone">Phone
                        <mask-input id="phone" v-model="phone" mask="+7 (###) ###-##-##"/>
                    </label>
                    <div class="error" v-if="errors.phone">{{ errors.phone }}</div>
                    <div class="error" v-if="errors.base">{{ errors.base }}</div>
                </div>
                <button @click.prevent.stop="sendForm" :class="{disabled: inProgress}">Send code</button>
            </form>
            <a href="/login">Already have an account?</a>
        </div>
        <div v-else>
            <form>
                <div class="iw">
                    <label for="code">Code
                        <input id="name" type="text" v-model="code"/>
                    </label>
                    <div class="error" v-if="errors.code">{{ errors.code }}</div>
                    <div class="error" v-if="errors.base">{{ errors.base }}</div>
                </div>
                <button @click.prevent.stop="sendCode" :class="{disabled: inProgress}">Confirm</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {MaskInput} from 'vue-3-mask';

export default {
    components: {
        MaskInput
    },
    computed: {
        normalizedPhone() {
            return this.phone.replace(/[^0-9]/g, '');
        }
    },
    props: {},
    data() {
        return {
            step: 1,
            phone: "",
            name: "",
            code: "",
            inProgress: false,
            errors: {
                phone: null,
                name: null,
                code: null,
                base: null
            }
        }
    },
    methods: {
        clearErrors() {
            this.errors.name = null;
            this.errors.phone = null;
            this.errors.base = null;
            this.errors.code = null;
        },
        validatePhone() {
            this.clearErrors();

            let valid = true;

            if (this.normalizedPhone.length !== 11) {
                valid = false;
                this.errors.phone = 'Введите корректный номер телефона';
            } else {
                this.errors.phone = null;
            }

            if (this.name.length < 2) {
                valid = false;
                this.errors.name = 'Введите имя';
            } else {
                this.errors.name = null;
            }

            if (this.step === 2 && this.code.length !== 4) {
                valid = false;
                this.errors.code = 'Введите код';
            } else {
                this.errors.code = null;
            }

            return valid;
        },
        sendForm() {
            if (this.inProgress)
                return;

            if (!this.validatePhone())
                return;

            this.inProgress = true;

            axios.post('/auth/register', {
                phone: this.normalizedPhone,
                name: this.name
            })
                .then(response => {
                    this.inProgress = false;

                    if (response.data.success === true) {
                        this.step = 2;
                        this.clearErrors();
                    } else {
                        this.clearErrors();
                        this.errors.base = "Something went wrong";
                    }
                })
                .catch((e) => {
                    if (e.response.status >= 400 && e.response.status < 500) {
                        this.inProgress = false;
                        this.clearErrors();
                        this.errors.base = e.response.data.message;
                    } else {
                        this.inProgress = false;
                        this.clearErrors();
                        this.errors.base = "Something went wrong";
                    }
                })
        },
        sendCode() {
            if (this.inProgress)
                return;

            if (!this.validatePhone())
                return;

            this.inProgress = true;

            axios.post('/auth/verify', {
                phone: this.normalizedPhone,
                code: this.code
            })
                .then(response => {
                    if (response.data.success === true) {
                        this.inProgress = false;
                        window.location.href = "/";
                    } else {
                        this.clearErrors();
                        this.errors.base = "Something went wrong";
                    }
                })
                .catch((e) => {
                    if (e.response.status >= 400 && e.response.status < 500) {
                        this.inProgress = false;
                        this.clearErrors();
                        this.errors.base = e.response.data.message;
                    } else {
                        this.inProgress = false;
                        this.clearErrors();
                        this.errors.base = "Something went wrong";
                    }
                })
        }
    }
}
</script>

<style scoped>
.wrapper {
    width: 100%;
    max-width: 500px;
    border-radius: 30px;
    padding: 40px;
    box-shadow: 0px 2px 20px 2px rgba(0, 0, 0, .1);

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;

        .iw {
            label {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 30px;
                font-size: 14px;
            }

            input {
                width: 200px;
                appearance: none;
                border: none;
                background: none;
                border-bottom: 1px solid rgba(0, 0, 0, .5);
                outline: none;
                text-align: center;

                &:focus {
                    outline: 0;
                    box-shadow: none;
                }
            }
        }

        .error {
            font-size: 12px;
            color: red;
            margin-top: 5px;
            text-align: center;
        }

        button {
            display: block;
            width: 200px;
            height: 50px;
            text-align: center;
            background: black;
            border-radius: 20px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;

            &.disabled {
                opacity: .6;
            }
        }
    }

    a {
        margin-top: 15px;
        display: block;
        font-size: 14px;
        color: grey;
        text-align: center;
    }
}
</style>
