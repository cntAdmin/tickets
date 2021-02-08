<template>
    <div :class="'col-' + size + ' mb-3'">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div :class="'text-xs font-weight-bold text-' + color + ' text-uppercase mb-1'">{{ title }}</div>
                        <div :class="'h5 mb-0 font-weight-bold text-' + color">{{ count }}</div>
                    </div>
                    <div class="col-auto">
                        <i :class="'fas fa-' + icon + ' fa-3x  text-' + color " ></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'title',
        'count',
        'color',
        'size',
        'icon'
    ],
    data() {
        return {}
    },
    mounted() {
        // this.getCount(this.count);
        // this.setIconColors()
    },
    methods: {
    }, // END METHODS
}
</script>