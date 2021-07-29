<template>
    <div class="composer">
        <v-textarea  auto-grow
        append-outer-icon="mdi-send"

        class="rounded-lg"
            dense
            :row-height="3"
                @click:append-outer="send"
             clearable v-model="message" @keydown.enter="send" placeholder="Message..."></v-textarea>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: ''
            };
        },
        methods: {
            send(e) {
                e.preventDefault();

                if (this.message == '') {
                    return;
                }

                this.$emit('send', this.message);
                this.message = '';
            }
        }
    }
</script>

<style lang="scss" scoped>
.composer textarea {
    width: 96%;
    margin: 10px;
    resize: none;
    border-radius: 3px;
    border: 1px solid lightgray;
    padding: 6px;
}
</style>

