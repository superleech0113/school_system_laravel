<template>
   <div>
       <div class="row">
           <div class="col-md-6 col-sm-12">
               <app-non-zero-class-stats :timezone="timezone"></app-non-zero-class-stats>
           </div>
           <div class="col-md-6 col-sm-12">
               <app-attendance-stats :timezone="timezone"></app-attendance-stats>
           </div>
       </div>
       <div class="row mt-3">
           <div class="col-md-6 col-sm-12">
               <app-total-graph-stats :timezone="timezone" byAction="not_recieved" :chartTitle="trans('messages.monthly_yearly_totals')"></app-total-graph-stats>
           </div>
           <div class="col-md-6 col-sm-12">
               <app-total-graph-stats :timezone="timezone" byAction="recieved" :chartTitle="trans('messages.per_customer_customer_type')"></app-total-graph-stats>
           </div>
       </div>
   </div>
</template>

<script>
import NonZeroClassStats from './NonZeroClassStats.vue';
import AttendanceStats from './AttendanceStats.vue';
import TotalGraphsStats from './TotalGraphsStats.vue';

export default {
    props: ['timezone'],
    components: {
        'app-non-zero-class-stats' : NonZeroClassStats,
        'app-attendance-stats': AttendanceStats,
        'app-total-graph-stats': TotalGraphsStats
    }
}
</script>
