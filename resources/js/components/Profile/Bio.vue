<template>
    <div>
        <div class="d-flex">
            <h2 class="h3 font-weight-bold mb-3">Biographie</h2>

            <button v-if="canUpdate && fields.bio"
                    class="btn btn-sm ml-auto align-self-start"
                    data-toggle="modal" data-target="#editBio">
                <span class="sr-only">Modifier</span>

                <svg class="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                    <path fill-rule="evenodd"
                          d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                </svg>
            </button>
        </div>

        <p class="mb-5">
            <span v-if="fields.bio" v-text="fields.bio"></span>

            <button v-else-if="canUpdate"
                    class="btn btn-sm btn-link p-0"
                    data-toggle="modal" data-target="#editBio">
                Ajouter ma biographie
            </button>
        </p>

        <div ref="modal" class="modal fade" id="editBio" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-white">
                        <h5 class="modal-title">Modifier biographie</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg class="bi bi-x" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                                <path fill-rule="evenodd"
                                      d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
                            <div class="form-group">
                                <label for="bio">Biographie</label>
                                <textarea :class="['form-control', form.errors.has('bio') ? 'is-invalid' : '' ]"
                                          id="bio"
                                          rows="3"
                                          v-model="form.bio"></textarea>

                                <div v-if="form.errors.has('bio')"
                                     class="invalid-feedback"
                                     v-text="form.errors.get('bio')">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-link mr-2" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import updateProfile from "../../mixins/update-profile";

    export default {
        mixins: [updateProfile],

        methods: {
            updated() {
                $(this.$refs.modal).modal('hide');
                flash('Biographie modifiée.');
            }
        }
    }
</script>
