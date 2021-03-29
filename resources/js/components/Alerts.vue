<template>
    <div id="alerts">
        <b-alert
            :show="dismissCountDown"
            :variant="messageType"
            dismissible
            fade
            @dismiss-count-down="countDownChanged">
            {{ message }}
        </b-alert>
    </div>
</template>

<script>
    export default {
        name: "Alerts",
        created() {
            this.$root.$on('alert', (message, messageType) => {
                this.message = message;
                this.messageType = messageType;
                this.showAlert();
            })
        },
        data() {
            return {
                dismissSecs: 3,
                dismissCountDown: 0,
                showDismissibleAlert: false,
                message: '',
                messageType: '',
            }
        },
        methods: {
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
                if (dismissCountDown === 0) {
                    this.message = '';
                    this.messageType = '';
                }
            },
            showAlert() {
                this.dismissCountDown = this.dismissSecs
            }
        }
    }
</script>

<style scoped>

</style>
