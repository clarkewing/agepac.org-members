<template>
    <div class="mb-5">
        <div class="d-flex align-items-start">
            <h5 class="align-self-end flex-grow-1"
                v-text="fields.title"
            ></h5>

            <button v-if="canUpdate"
                    class="btn btn-sm ml-2"
                    data-toggle="modal" :data-target="'#' + 'editCourse' + _uid">
                <span class="sr-only">Modifier</span>

                <svg class="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                    <path fill-rule="evenodd"
                          d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                </svg>
            </button>

            <div ref="modal" class="modal fade" :id="'editCourse' + _uid" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-white">
                            <h5 class="modal-title">Modifier emploi</h5>
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
                            <course-form v-bind:form.sync="form"
                                             show-delete
                                             @destroy="destroy"
                                             @submit="update"
                            ></course-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap">
            <h6 class="flex-shrink-0 small text-muted mr-4">
                <svg class="bi fa-graduation-cap mr-1" width="1em" height="1em" viewBox="0 0 640 512"
                     fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M606.72 147.91l-258-79.57c-18.81-5.78-38.62-5.78-57.44 0l-258 79.57C13.38 154.05 0 171.77 0 192.02s13.38 37.97 33.28 44.11l22.64 6.98c-2.46 5.19-4.4 10.62-5.7 16.31C39.53 264.6 32 275.33 32 288.01c0 10.78 5.68 19.85 13.86 25.65L20.33 428.53C18.11 438.52 25.71 448 35.95 448h56.11c10.24 0 17.84-9.48 15.62-19.47L82.14 313.66c8.17-5.8 13.86-14.87 13.86-25.65 0-10.6-5.49-19.54-13.43-25.36 1.13-3.55 2.96-6.67 4.85-9.83l54.87 16.92L128 384c0 35.34 85.96 64 192 64s192-28.65 192-64l-14.28-114.26 109-33.62c19.91-6.14 33.28-23.86 33.28-44.11s-13.38-37.96-33.28-44.1zM462.44 374.47c-59.7 34.2-225.9 33.78-284.87 0l11.3-90.36 102.42 31.59c11.15 3.43 32.24 7.77 57.44 0l102.42-31.59 11.29 90.36zM334.59 269.82c-9.44 2.91-19.75 2.91-29.19 0L154.62 223.3l168.31-31.56c8.69-1.62 14.41-9.98 12.78-18.67-1.62-8.72-10.09-14.36-18.66-12.76l-203.78 38.2c-6.64 1.24-12.8 3.54-18.71 6.27L53.19 192l252.22-77.79c9.44-2.91 19.75-2.91 29.19 0l252.22 77.82-252.23 77.79z"/>
                </svg>
                <span class="sr-only">Organisme :</span>

                <span v-text="fields.school"></span>
            </h6>

            <h6 class="flex-shrink-0 flex-grow-1 small text-muted mr-4" v-if="fields.location">
                <svg class="bi bi-geo mr-1" width="1em" height="1em" viewBox="0 0 16 16"
                     fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 4a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path d="M7.5 4h1v9a.5.5 0 0 1-1 0V4z"/>
                    <path fill-rule="evenodd"
                          d="M6.489 12.095a.5.5 0 0 1-.383.594c-.565.123-1.003.292-1.286.472-.302.192-.32.321-.32.339 0 .013.005.085.146.21.14.124.372.26.701.382.655.246 1.593.408 2.653.408s1.998-.162 2.653-.408c.329-.123.56-.258.701-.382.14-.125.146-.197.146-.21 0-.018-.018-.147-.32-.339-.283-.18-.721-.35-1.286-.472a.5.5 0 1 1 .212-.977c.63.137 1.193.34 1.61.606.4.253.784.645.784 1.182 0 .402-.219.724-.483.958-.264.235-.618.423-1.013.57-.793.298-1.855.472-3.004.472s-2.21-.174-3.004-.471c-.395-.148-.749-.336-1.013-.571-.264-.234-.483-.556-.483-.958 0-.537.384-.929.783-1.182.418-.266.98-.47 1.611-.606a.5.5 0 0 1 .595.383z"/>
                </svg>
                <span class="sr-only">Lieu :</span>

                <span v-text="fields.location.name"></span>
            </h6>

            <h6 class="flex-shrink-0 small text-muted">
                <svg class="bi bi-calendar mr-1" width="1em" height="1em" viewBox="0 0 16 16"
                     fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1zm1-3a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2z"/>
                    <path fill-rule="evenodd"
                          d="M3.5 0a.5.5 0 0 1 .5.5V1a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 .5-.5zm9 0a.5.5 0 0 1 .5.5V1a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 .5-.5z"/>
                </svg>
                <span class="sr-only">Dates :</span>

                <span>
                    {{ startDate }} - {{ endDate }}
                </span>
            </h6>
        </div>

        <p v-if="fields.description" v-text="fields.description"></p>
    </div>
</template>

<script>
    import {Form} from 'vform';
    import moment from 'moment';
    import CourseForm from "./CourseForm";
    import updateProfile from "../../mixins/update-profile";

    export default {
        mixins: [updateProfile],
        components: {CourseForm},

        props: [
            'id',
            'title',
            'school',
            'location',
            'description',
            'start_date',
            'end_date',
        ],

        data() {
            return {
                endpoint: '/courses',
                resourceId: this.id,

                form: new Form(_.pick(this.$props, [
                    'title',
                    'school',
                    'location',
                    'description',
                    'start_date',
                    'end_date',
                ])),

                fields: _.omit(this.$props, []), // _.omit produces an independent copy
            };
        },

        computed: {
            startDate() {
                return moment(this.fields.start_date).format('LL');
            },

            endDate() {
                if (this.end_date) {
                    return moment(this.fields.end_date).format('LL');
                }
            }
        },

        methods: {
            updated() {
                $(this.$refs.modal).modal('hide');
                flash('Études modifiées.');
            },

            deleted() {
                $(this.$refs.modal).modal('hide');

                flash('Études supprimées.');

                this.$emit('destroyed', this.fields.id);
            },
        },

        created() {
            moment.locale('fr');
        }
    }
</script>
