<template>
    <div id="ingredients">
        <b-button v-b-modal.ingredientModal variant="warning" size="sm" class="mb-3">
            Add ingredient
        </b-button>
        <div v-if="ingredients.length">
            <h6>Ingredients:</h6>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Retail price</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(ingredient, index) in ingredients">
                    <td>
                        {{ ingredient.name }}
                        <span class="badge badge-success float-right" v-if="ingredient.is_alcohol">
                            Alcoholic
                        </span>
                    </td>
                    <td>
                    <span class="text-success font-weight-bold">
                        {{ ingredient.price }} &euro;
                    </span>
                    </td>
                    <td>
                    <span class="text-success font-weight-bold">
                        {{ ingredient.retail_price }} &euro;
                    </span>
                    </td>
                    <td>
                        <a @click="prepareToEdit(index, ingredient.id)" class="btn btn-sm btn-outline-primary">
                            Edit
                        </a>
                        <a @click="deleteIngredient(index, ingredient.id)" class="btn btn-sm btn-outline-danger">
                            Remove
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center" v-else>
            <h4>There are not ingredients :(</h4>
        </div>
        <b-modal id="ingredientModal" title="Add ingredient" @ok="saveIngredient">
            <form autocomplete="off">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control form-control-sm" id="name" v-model="form.name">
                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control form-control-sm" max="50" step="0.01" id="price" v-model="form.price">
                    <span class="text-danger" v-if="errors.price">{{ errors.price[0] }}</span>
                </div>
                <div class="mb-3">
                    <b-form-checkbox id="is_alcohol" v-model="form.is_alcohol" name="is_alcohol">
                        Is alcohol
                    </b-form-checkbox>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "Ingredients",
        created() {
            this.getIngredients();
            this.$root.$on('bv::modal::hidden', (event) => {
                if (event.componentId === 'ingredientModal') {
                    this.cleanData()
                }
            });

            this.$root.$on('alert', () => {
                this.$bvModal.hide('ingredientModal')
            });
        },
        data() {
            return {
                form: {
                    name: '',
                    price: '',
                    is_alcohol: false
                },
                currentID: '',
                currentIngredient: '',
                ingredients: [],
                errors: [],
                BASE_URL: '/api/ingredients/'
            }
        },
        methods: {
            getIngredients() {
                axios.get(this.BASE_URL).then(response => {
                    this.ingredients = response.data.data;
                }).catch(error => {
                    console.error('Cannot load ingredients');
                });

            },
            storeIngredient() {
                axios.post(this.BASE_URL, this.form).then(response => {
                    if (response.status === 201) {
                        this.showAlert('Ingredient successfully added', 'success');
                        this.ingredients.push(response.data.data);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.showMessage(error.response.data.message, 'Error', 'danger');
                    }
                });
            },
            prepareToEdit(index, id) {
                let ingredient = this.ingredients[index]
                if (typeof ingredient !== 'undefined') {
                    this.currentID = id;
                    this.currentIngredient = index;
                    this.form.name = ingredient.name;
                    this.form.price = ingredient.price;
                    this.form.is_alcohol = ingredient.is_alcohol;
                    this.$bvModal.show('ingredientModal')
                }
            },
            updateIngredient() {
                axios.put(this.BASE_URL + this.currentID, this.form).then(response => {
                    let ingredient = response.data.data;

                    this.showAlert('Ingredient ' + ingredient.name + ' successfully updated', 'success');
                    this.ingredients[this.currentIngredient] = ingredient;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.showMessage(error.response.data.message, 'Error', 'danger');
                    }
                })
            },
            saveIngredient(bvModalEvt) {
                bvModalEvt.preventDefault();
                if (this.currentIngredient === '') {
                    this.storeIngredient();
                } else {
                    this.updateIngredient();
                }
            },
            deleteIngredient(item, id) {
                this.callConfirm('Are you sure you want to delete this ingredient ?').then(value => {
                    if (value) {
                        axios.delete(this.BASE_URL + id).then(response => {
                            let ingredient = response.data.data;

                            this.showAlert('Ingredient ' + ingredient.name + ' was deleted', 'danger');
                            this.ingredients.splice(item, 1);
                        }).catch(error => {
                            this.showMessage(error.response.data.message, 'Error', 'danger');
                        });
                    }
                });
            },
            cleanData() {
                this.form.name = '';
                this.form.price = '';
                this.form.is_alcohol = false;
                this.currentID = '';
                this.currentIngredient = '';
                this.errors = [];
            },
        }
    }
</script>

<style scoped>

</style>
