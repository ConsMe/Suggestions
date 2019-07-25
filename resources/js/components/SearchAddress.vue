<template>
    <div>
        <md-autocomplete
            v-model="address"
            :md-options="addresses"
            style="z-index: 999999999;"
            md-layout="box"
        >
            <label>Адрес</label>
        </md-autocomplete>
    </div>
</template>

<style>
.md-autocomplete-box-content {
    background: white;
}
</style>


<script>
export default {
    data() {
        return {
            addresses: [],
            address: null
        }
    },
    watch: {
        address() {
            this.debouncedGetSuggestion()
        }
    },
    created() {
        this.debouncedGetSuggestion = _.debounce(this.getSuggestion, 500)
    },
    methods: {
        getSuggestion() {
            if (!this.address || this.address.length < 3) {
                this.addresses = []
                return
            }
            axios
            .get("/suggest?str=" + this.address)
            .then(response => {
                this.addresses = new Promise(resolve => {
                    let data = response.data[1].map(address => {
                        return address[1]
                    })
                    resolve(data)
                })
            })
            .catch(function(error) {
                console.error('Что-то пошло не так')
            });
        }
    }
};
</script>
