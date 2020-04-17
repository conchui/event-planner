<template>

    <div class="container">
        <div class="row pt-3">
            <form class="col-3" @submit.prevent>
                <h3 class="mb-5">Calendar</h3>

                <transition name="fade">
                    <p class="alert alert-success" role="alert" v-if="tooltip">Saved</p>
                </transition>

                <div class="form-group">
                    <label>Event Name</label>
                    <input class="form-control" type="text" v-model="event.eventName">
                    <div class="text-danger" v-if="errors.eventName">{{errors.eventName[0]}}</div>
                </div>  

                <div class="form-group">
                    <label>Start Date</label>
                    <input class="form-control" type="date" v-model="event.startDate">
                    <div class="text-danger" v-if="errors.startDate">{{errors.startDate[0]}}</div>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input class="form-control" type="date" v-model="event.endDate">
                    <div class="text-danger" v-if="errors.endDate">{{errors.endDate[0]}}</div>
                </div>

                <div class="form-group">
                    <div class="form-check form-check-inline" v-for="day in daysInWeek" v-bind:key="day.value">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" v-bind:value="day.value" v-model="event.daysActive">
                            <code class="text-dark">{{ day.label }}</code>
                        </label>
                    </div>
                    <div class="text-danger" v-if="errors.daysActive">{{errors.daysActive[0]}}</div>
                </div>

                <button class="btn btn-secondary mr-1" @click="resetFields()">Reset</button>
                <button class="btn btn-primary mr-1" @click="saveEvent()">Submit</button>
            </form>

            <div class="col-9">
                <Fullcalendar @eventClick="selectEvent" :plugins="calendarPlugins" :events="events"/>
            </div>
        </div>
    </div>

</template>

<script>
    import Fullcalendar from "@fullcalendar/vue";
    import dayGridPlugin from "@fullcalendar/daygrid";
    import interactionPlugin from "@fullcalendar/interaction";

    export default {
        components: {
            Fullcalendar
        },

        props: {
            'api_url' : String,
        },

        mounted() {
            this.getAllEvents();
        },

        data() {
            return {
                calendarPlugins: [dayGridPlugin, interactionPlugin],
                eventId: "",
                events: [],
                event: {
                    eventName  : "",
                    startDate  : "",
                    endDate    : "",
                    daysActive : [],
                },
                daysInWeek: [
                    {label: 'Sun', value: 0},
                    {label: 'Mon', value: 1},
                    {label: 'Tue', value: 2},
                    {label: 'Wed', value: 3},
                    {label: 'Thu', value: 4},
                    {label: 'Fri', value: 5},
                    {label: 'Sat', value: 6},
                ],
                errors: [],
                tooltip: false,
            };
        },

        methods : {

            hideTooltip() {
                setTimeout(() => {
                    this.tooltip = false;
                }, 2000);
            },

            resetFields() {
                this.eventId = "";
                this.event =  {
                    eventName  : "",
                    startDate  : "",
                    endDate    : "",
                    daysActive : [],
                };
            },

            selectEvent(values) {

                let recurValues = values.event._def.recurringDef.typeData,
                    startRecur  = new Date(recurValues.startRecur).toISOString().slice(0,10),
                    endRecur    = new Date(recurValues.endRecur).toISOString().slice(0,10);

                this.eventId = values.event.id;
                this.event   =  {
                    eventName  : values.event.title,
                    startDate  : startRecur,
                    endDate    : endRecur,
                    daysActive : recurValues.daysOfWeek,
                };
            },

            saveEvent() {
                if (this.eventId !== "") this.event.eventId = this.eventId;

                axios
                    .post(this.api_url, this.event)
                    .then(response => {
                        this.tooltip = true;
                        this.hideTooltip();

                        this.eventId = response.data;
                        this.errors  = [];
                        this.getAllEvents();
                    })
                    .catch(error => {
                        if (error.response) {
                            this.errors = error.response.data.errors;
                        }
                    });
            },

            getAllEvents() {
                axios
                    .get(this.api_url)
                    .then(response => {
                        let rawEvents  = response.data.data,
                            allEvents  = [];

                        $.map(rawEvents, function(event){
                            event.daysActive = JSON.parse(event.daysActive);
                            
                            let buildEvent = {};

                            buildEvent.title      = event.eventName;
                            buildEvent.startRecur = event.startDate;
                            buildEvent.endRecur   = event.endDate;
                            buildEvent.id         = event.eventId;
                            buildEvent.daysOfWeek = event.daysActive;

                            allEvents.push(buildEvent);
                            
                            return event;
                        });

                        this.events = allEvents;
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
                }
        }
    };
</script>

<style lang="css">
    @import "~@fullcalendar/core/main.css";
    @import "~@fullcalendar/daygrid/main.css";
</style>