<template>
    <input type="file" accept="image/*" class="hidden" ref="file" @change="change">
    <div class="relative inline-block h-36 w-36 overflow-hidden rounded-lg md:rounded-full">
        <img :src="src" alt="avatar" class="h-36 w-36 object-cover rounded-lg ">
        <div class="absolute top-0 w-full h-full bg-black bg-opacity-25 flex items-center justify-center">
            <button type="button" @click="browse()" class="rounded-full hover:bg-white hover:bg-opacity-25 focus:outline-none text-white transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InputAvatar",
        props: {
            value: File,
            defaultSrc: String
        },
        data(){
          return {
              src: this.defaultSrc,
          }
        },
        methods: {
            browse() {
                this.$refs.file.click()
            },
            remove() {
                this.src = this.defaultSrc
                this.$emit('input', null);
            },

            change(e) {
                this.$emit('input', e.target.files[0]);
                let reader = new FileReader()
                reader.readAsDataURL(e.target.files[0]);
                reader.onload = (e) => {
                    this.src = e.target.result;
                }
            }
        }
    }
</script>

<style scoped>

</style>
