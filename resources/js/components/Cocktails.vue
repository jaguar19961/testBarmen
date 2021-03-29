<template>
    <div id="cocktails">
        <b-button v-b-modal.cocktailModal variant="warning" size="sm" class="mb-3" v-if="isAuthorized">
            Add cocktail
        </b-button>


        <div v-if="cocktails.length">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 mx-auto" v-for="(cocktail, index) in cocktails">
                    <div class="card text-dark bg-light border border-primary rounded mb-3" style="max-width: 18rem;">
                        <div class="card-header">
                            <span>
                                {{ cocktail.name }}
                            </span>
                            <span class="badge badge-success float-right" v-if="cocktail.has_alcohol">
                                Alcoholic
                            </span>
                        </div>
                        <div class="card-body">
                            <span class="font-weight-bold">Ingredient list:</span>

                            <ul class="list-unstyled" v-if="cocktail.ingredients.length">
                                <li v-for="ingredient in cocktail.ingredients">
                                    <small>
                                        {{ ingredient.name }}
                                    </small>
                                </li>
                            </ul>

                            <small class="font-weight-bold">
                                Price:
                                <span class="text-success">
                                    {{ cocktail.price }} &euro;
                                </span>
                            </small>

                            <div class="actions" v-if="isAuthorized">
                                <a href="javascript:void(0);" @click="prepareToEdit(index, cocktail.id)"
                                   class="float-left">
                                    <small>Edit</small>
                                </a>
                                <a href="javascript:void(0);" @click="deleteCocktail(index, cocktail.id)"
                                   class="text-danger float-right">
                                    <small>Remove</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div v-else class="text-center">
            <h4>There are no cocktails :(</h4>
        </div>


        <b-modal id="cocktailModal" title="Add cocktail" @ok="saveCocktail" v-if="isAuthorized">
            <form autocomplete="off">

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control form-control-sm" id="name" v-model="form.name">
                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                </div>

                <div class="mb-3" v-if="ingredientList.length">
                    <b-form-group label="Ingredients:" v-slot="{ ariaDescribedby }">

                        <b-form-checkbox-group v-model="form.ingredients" :aria-describedby="ariaDescribedby">
                            <div v-for="ingredient in ingredientList">
                                <b-form-checkbox :value="ingredient">
                                    {{ ingredient.name }} <small>({{ ingredient.retail_price }} &euro;)</small>
                                </b-form-checkbox>
                            </div>
                        </b-form-checkbox-group>

                    </b-form-group>
                    <p>
                        Estimated price:
                        <span class="text-success">
                            {{ estimatedPrice }} &euro;
                        </span>
                    </p>
                </div>

                <p class="text-danger" v-if="errors.ingredients">{{ errors.ingredients[0] }}</p>

                <div class="mb-3" v-if="form.ingredients.length">
                    <h6>Sort ingredients:</h6>

                    <draggable v-model="form.ingredients" group="people" @start="drag=true" @end="drag=false">
                        <div v-for="ingredient in form.ingredients" class="sortable__item" :key="ingredient.position">
                            <span>{{ ingredient.name }}</span>
                            <span class="badge badge-position badge-success" v-if="form.ingredients.length > 1">
                                {{ ingredient.position }}
                            </span>
                        </div>
                    </draggable>

                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        name: "Cocktails",
        props: {
            authorized: [Boolean, String]
        },
        components: {
            draggable,
        },
        computed: {
            estimatedPrice() {
                let sum = this.form.ingredients.reduce((a, b) => a + parseFloat(b.retail_price), 0);
                return roundNumber(sum, 2);
            },
            isAuthorized() {
                return this.authorized === 'true';
            }
        },
        created() {
            this.getCocktails();
            this.$root.$on('bv::modal::show', (event) => {
                if (event.componentId === 'cocktailModal') {
                    this.getIngredients();
                }
            })

            this.$root.$on('bv::modal::hidden', (event) => {
                if (event.componentId === 'cocktailModal') {
                    this.cleanData();
                }
            })

            this.$root.$on('alert', () => {
                this.$bvModal.hide('cocktailModal')
            });
        },
        watch: {
            "form.ingredients": {
                handler() {
                    this.sortByProperty(this.form.ingredients, 'position');
                    this.orderIngredientsBySelected();
                }
            }
        },
        data() {
            return {
                form: {
                    name: '',
                    ingredients: []
                },
                currentID: '',
                currentCocktail: '',
                cocktails: [],
                ingredientList: [],
                errors: [],
                BASE_URL: '/api/cocktails/',
            }
        },
        methods: {
            getCocktails() {
                axios.get(this.BASE_URL).then(response => {
                    this.cocktails = response.data.data;
                }).catch(error => {
                    this.showMessage('Cannot load cocktails', 'Error', 'danger');
                });
            },
            getIngredients() {
                axios.get('/api/ingredients').then(response => {
                    this.ingredientList = response.data.data;

                    this.orderIngredientsBySelected();
                }).catch(error => {
                    this.showMessage('Cannot load ingredients', 'Error', 'danger');
                });
            },
            storeCocktail() {
                axios.post(this.BASE_URL, this.form).then(response => {
                    if (response.status === 201) {
                        let newCoctail = response.data.data;

                        this.showAlert('Cocktail ' + newCoctail.name + ' successfully added', 'success');
                        this.cocktails.push(newCoctail);
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
                let cocktail = this.cocktails[index]
                if (typeof cocktail !== 'undefined') {
                    this.currentID = id;
                    this.currentCocktail = index;
                    this.form.name = cocktail.name;
                    this.form.ingredients = cocktail.ingredients;
                    this.$bvModal.show('cocktailModal')
                }
            },
            updateCocktail() {
                axios.put(this.BASE_URL + this.currentID, this.form).then(response => {
                    let updatedCocktail = response.data.data;

                    this.showAlert('Cocktail ' + updatedCocktail.name + ' successfully updated', 'success');
                    this.cocktails[this.currentCocktail] = updatedCocktail;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.showMessage(error.response.data.message, 'Error', 'danger');
                    }
                })
            },
            saveCocktail(bvModalEvt) {
                bvModalEvt.preventDefault();
                if (this.ingredientList.length) {
                    if (this.currentCocktail === '') {
                        this.storeCocktail();
                    } else {
                        this.updateCocktail();
                    }
                } else {
                    console.error('Ingredient list is empty !');
                    this.showMessage(
                        'You must create at least one ingredient to be able to add cocktails !',
                        'Warning',
                        'warning'
                    );
                }
            },
            deleteCocktail(item, id) {
                this.callConfirm('Are you sure you want to delete this cocktail ?').then(value => {
                    if (value) {
                        axios.delete(this.BASE_URL + id).then(response => {
                            let cocktail = response.data.data;

                            this.showAlert('Cocktail ' + cocktail.name + ' was deleted', 'danger');
                            this.cocktails.splice(item, 1);
                        }).catch(error => {
                            this.showMessage(error.response.data.message, 'Error', 'danger');
                        });
                    }
                });
            },
            cleanData() {
                this.form.name = '';
                this.form.ingredients = [];
                this.currentID = '';
                this.currentCocktail = '';
                this.errors = [];
            },
            orderIngredientsBySelected() {
                if (this.currentCocktail !== '') {
                    this.ingredientList.map(ingredient => {
                        let selected = this.form.ingredients.filter(
                            selectedIngredient => selectedIngredient.id === ingredient.id
                        );
                        selected = selected[0];

                        ingredient.position = (typeof selected !== 'undefined') ? selected.position : null

                        return ingredient;
                    }).map(ingredient => {
                        if (ingredient.position === null) {
                            let lastPosition = Math.max.apply(Math, cloneObject(this.ingredientList).map((ingt) => ingt.position));
                            ingredient.position = lastPosition + 1;
                        }

                        return ingredient;
                    });
                }
            },
        }
    }
</script>

<style scoped>
    .sortable__item {
        padding: 3px 10px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        cursor: move;
        margin-bottom: 7px;
    }

    .sortable__item .badge-position {
        float: right;
        margin-top: 3px;
    }
</style>
