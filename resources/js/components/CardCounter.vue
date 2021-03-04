<template>
    <div :class="'col-6 col-lg-' + size + ' mb-3'">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <div class="ml-auto mr-xl-auto order-1 order-xl-0 text-center text-xl-left w-100">
                        <div :class="'text-xs font-weight-bold text-' + color + ' text-uppercase mb-1'">{{ title }}</div>
                        <div :class="'h5 mb-0 font-weight-bold text-' + color">{{ count }}</div>
                    </div>
                    <div class="mr-auto ml-xl-auto order-0 order-xl-1">
                        <i :class="'fas fa-' + icon + ' fa-2x text-' + color " ></i>
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