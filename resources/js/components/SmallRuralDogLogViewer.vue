<template>
    <el-container>
        <el-header>
            <el-menu
                :default-active="activeIndex"
                mode="horizontal"
                @select="handleSelect"
            >
                <el-menu-item index="1">
                    <i class="el-icon-s-platform"></i>
                    <span slot="title">Dashboard</span>
                </el-menu-item>
                <el-menu-item index="2">
                    <i class="el-icon-menu"></i>
                    <span slot="title">Logs</span>
                </el-menu-item>
            </el-menu>
        </el-header>
        <el-main>
            <div>
                <Dashboard
                v-if="chart_data && activeIndex == '1'"
                :chartData="chart_data.chartData"
                :percents="chart_data.percents"
            />
            <LogList v-if="activeIndex == '2'" :attrs="attrs" @onDelete="get_chart_data" />
            </div>
        </el-main>
    </el-container>
</template>
<script>
import Dashboard from "./Dashboard";
import LogList from "./LogList";
export default {
    components: {
        Dashboard,
        LogList
    },
    props: {
        attrs: Object
    },
    data() {
        return {
            activeIndex: "2",
            chart_data: null
        };
    },
    mounted() {
        this.get_chart_data();
    },
    methods: {
        get_chart_data() {
            this.$http.get(this.attrs.routers.get_chart_data).then(res => {
                this.chart_data = res;
            });
        },
        handleSelect(index) {
            this.activeIndex = index;
        }
    }
};
</script>
<style lang="scss" scoped>
.el-header {
    padding: 0;
}
</style>
