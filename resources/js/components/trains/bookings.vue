<template>
    <div class="card">
        <div class="card-body">
            <div class="loading" v-if="loading">
                <img src="images/loading.gif" alt="">
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <input type="text" value="" name="search" placeholder="Search Train"
                               aria-label="Search Student" aria-describedby="Search Train" @input="search"
                               class="form-control" autocomplete="off" v-model="search_data">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <button type="button" class="btn btn-primary float-right text-white btn-block" @click="search">Search Train
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
                                <th>Available Seats</th>
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
                                <td> {{ train.avialble_seats }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Action Buttons">
                                        <button v-if="!train.hide" :disabled="train.not_bookable" type="button" class="btn btn-primary float-right text-white btn-block"
                                                @click="showBooking(train.id)">
                                           Book
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
                                <label for="passenger_name"
                                       class="col-md-4 col-form-label text-md-right"> Passenger Name</label>
                                <div class="col-md-6">
                                    <input id="passenger_name" type="text"
                                           class="form-control" v-model="passenger_data.name" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="contact_no"
                                       class="col-md-4 col-form-label text-md-right"> Contact No</label>
                                <div class="col-md-6">
                                    <input id="contact_no" type="text"
                                           class="form-control" v-model="passenger_data.contact_no" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nic"
                                       class="col-md-4 col-form-label text-md-right"> NIC</label>
                                <div class="col-md-6">
                                    <input id="nic" type="text"
                                           class="form-control" v-model="passenger_data.nic" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary float-right" @click="bookTrain">Save
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
            passenger_data: {
                nic: "",
                name: "",
                contact_no: "",
                train_id: "",
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


                })
                .finally(() => {
                    this.loading = false;
                });
        },
        bookTrain(){
            axios.post(flagsUrl + 'add_reservation', this.passenger_data)
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
                            text: "Train Reserved Successfully",
                            icon: "success"
                        }).then(function () {

                        });
                        this.getTrains();
                        this.closeModal();
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => {

                });
        },
        showBooking(train_id){
            $("#train_modal").modal('show');
            this.passenger_data.train_id = train_id;
        },
        closeModal(){
            $("#train_modal").modal('hide');
        },
    },

}
</script>

<style scoped>

</style>
