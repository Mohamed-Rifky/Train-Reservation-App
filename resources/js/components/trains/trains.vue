<template>
    <div class="card">
        <div class="card-body">
            <div class="loading" v-if="loading">
                <img src="images/loading.gif" alt="">
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <input type="text" value="" name="search" placeholder="Search Train"
                               aria-label="Search Student" aria-describedby="Search Train" @input="search"
                               class="form-control" autocomplete="off" v-model="search_data">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <button type="button" class="btn btn-primary float-right text-white" @click="search">Search Train
                        </button>
                        <button type="button" class="btn btn-danger float-right text-white" @click="showTrainModal">Add Train
                        </button>
                    </div>
                </div>

            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Departure Date</th>
                                <th>Departure Time</th>
                                <th>No Of Seats</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(train,key) in trains.data">
                                <td> {{ key + 1 }}</td>
                                <td> {{ train.train_name }}</td>
                                <td> {{ train.date }}</td>
                                <td> {{ train.time }}</td>
                                <td> {{ train.no_of_seats }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <button type="button" class="btn btn-secondary btn-sm" @click="getTrain(train.id)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="trains.hasOwnProperty('data') && trains.data.length === 0  && !loading">
                                <td colspan="8" class="text-center h4">No Trains Found</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <pagination :data="trains" :limit="2" v-if="searchByFilters"
                                    @pagination-change-page="search"></pagination>
                        <pagination :data="trains" :limit="2" @pagination-change-page="getTrains"
                                    v-else></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="train_modal" tabindex="-1" role="dialog"
             aria-labelledby="train_modal" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-body py-0">
                        <div class="p-2">
                            <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close" @click="closeModal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="main-content">
                            <h5>{{ widowName }}</h5>
                            <div class="form-group row">
                                <label for="name"
                                       class="col-md-4 col-form-label text-md-right"> Train Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control" v-model="train_data.name" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="departure_time_date"
                                       class="col-md-4 col-form-label text-md-right"> Train Departure Date & Time</label>
                                <div class="col-md-6">
                                    <input id="departure_time_date" type="datetime-local"
                                           class="form-control" v-model="train_data.departure_time_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_of_seats"
                                       class="col-md-4 col-form-label text-md-right"> No Of Seats</label>
                                <div class="col-md-6">
                                    <input id="no_of_seats" type="number"
                                           class="form-control" v-model="train_data.no_of_seats" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" v-if="edit" class="btn btn-secondary float-right" @click="editTrain">Save
                                </button>
                                <button type="button" v-else class="btn btn-secondary float-right" @click="addTrain">Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";
export default {
    name: "trains",
    components: {},
    data() {
        return {
            loading: false,
            searchByFilters: false,
            trains: {},
            search_data: "",
            widowName: "",
            edit : false,
            train_data: {
                id: "",
                name: "",
                departure_time_date: "",
                no_of_seats: "",
            },

        }
    },
    mounted() {
        this.getTrains();
    },
    methods: {
        getTrains(page = 1) {
            this.loading = true;
            this.searchByFilters = false;
            axios.get(flagsUrl + 'get_trains?page=' + page)
                .then((data) => {
                    this.trains = data.data;
                })
                .catch((error) => console.log(error))
                .finally(() => {
                    this.loading = false;
                });
        },
        showTrainModal() {
            $("#train_modal").modal('show');
            this.edit = false;
        },
        search(page = 1) {
            this.searchByFilters = true;
            this.loading = true;
            axios.post(flagsUrl + 'get_trains?page=' + page, {
                search: this.search_data,
            })
                .then(response => {
                    this.trains = response.data;
                }).finally(() => {
                this.loading = false;
            });
        },
        getTrain(id){
            this.loading = true;
            this.edit = true;
            axios.get(flagsUrl + 'get_train/' + id)
                .then((response) => {
                    $("#train_modal").modal('show');
                    this.train_data.name = response.data.train_name;
                    this.train_data.departure_time_date = response.data.departure_date_time;
                    this.train_data.no_of_seats = response.data.no_of_seats;
                    this.train_data.id = response.data.id;
                })
               .finally(() => {
                this.loading = false;
            });
        },
        addTrain() {
            axios.post(flagsUrl + 'add_train', this.train_data)
                .then((data) => {
                    if (data.data.status === false) {
                        let html = "<div class='container small'><ul class='list-group'>";
                        $.each(data.data.error, (errorKey, errorValue) => {
                            html += "<li class='list-group-item text-left'>" + errorValue[0] + "</li>";
                        });
                        html += "</ul></div>";
                        Swal.fire({
                            title: 'Error',
                            icon: 'error',
                            html: html,
                        });
                    } else {
                        Swal.fire({
                            title: "Success",
                            text: "Train Added!",
                            icon: "success"
                        }).then(function () {

                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => {
                    this.getTrains();
                    this.closeModal();
                });
        },
        editTrain(){
            axios.post(flagsUrl + 'update_train', this.train_data)
                .then((data) => {
                    if (data.data.status === false) {
                        let html = "<div class='container small'><ul class='list-group'>";
                        $.each(data.data.error, (errorKey, errorValue) => {
                            html += "<li class='list-group-item text-left'>" + errorValue[0] + "</li>";
                        });
                        html += "</ul></div>";
                        Swal.fire({
                            title: 'Error',
                            icon: 'error',
                            html: html,
                        });
                    } else {
                        Swal.fire({
                            title: "Success",
                            text: "Train Updated!",
                            icon: "success"
                        }).then(function () {

                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => {
                    this.getTrains();
                    this.closeModal();
                });
        },
        closeModal(){
            $("#train_modal").modal('hide');
        },
    },

}
</script>

<style scoped>

</style>
