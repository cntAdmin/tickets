<template>
    <div class="col-xl-3 col-md-7 mb-3">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div :class="'text-xs font-weight-bold text-' + color + ' text-uppercase mb-1'">{{ title ? title : 'Total' }}</div>
                        <div :class="'h5 mb-0 font-weight-bold text-' + color">{{ counter }}</div>
                    </div>
                    <div class="col-auto">
                        <i :class="'fas fa-' + icon + ' fa-3x text-' + color "></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'count',
        'title',
    ],
    data() {
        return {
            counter: 0,
            icon:'',
            color:'',
        }
    },
    mounted() {
        this.getCount(this.count);
        this.setIconColors()
    },
    methods: {
        getCount(count) {
            axios.get('/get_ticket_counter/' + count)
                .then(res => {
                    this.counter = res.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        setIconColors() {
            switch (this.title) {
                case 'Abierto':
                    this.icon = 'sticky-note';
                    this.color = 'secondary';
                    break;
                case 'Cerrado':
                    this.icon = 'check-circle';
                    this.color = 'info';
                    break;
                case 'Resuelto':
                    this.icon = 'check-double';
                    this.color = 'success';
                    break;
            
                default:
                    this.icon = 'clipboard-list';
                    this.color = 'primary';
                    break;
            }
        },
    }, // END METHODS
}
</script>