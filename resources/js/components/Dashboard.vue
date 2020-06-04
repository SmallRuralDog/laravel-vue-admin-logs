<template>
    <div class="m-15">
        <el-row>
            <el-col :span="8">
                <div ref="DonutView"></div>
            </el-col>
            <el-col :span="16">
                <el-row :gutter="20">
                    <el-col
                        :span="8"
                        v-for="(percent, key) in percents"
                        :key="key"
                    >
                        <el-card
                            shadow="hover"
                            class="mt-10"
                            :style="'color:' + percent.backgroundColor"
                        >
                            <div class="">
                                <div class="title">
                                    {{ percent.name }}
                                </div>
                                <p class="">
                                    {{ percent.count }} entries -
                                    {{ percent.percent }}%
                                </p>
                                <div>
                                    <el-progress
                                        :percentage="percent.percent"
                                        :text-inside="true"
                                        :stroke-width="18"
                                        :color="percent.backgroundColor"
                                    ></el-progress>
                                </div>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import { Pie } from "@antv/g2plot";
export default {
    props: {
        chartData: Object,
        percents: Object
    },
    data() {
        return {
            antv: null
        };
    },
    mounted() {
        let Vdata = this.chartData.labels.map((item, index) => {
            return {
                type: item,
                value: this.chartData.datasets[0].data[index]
            };
        });

        this.antv = new Pie(this.$refs["DonutView"], {
            forceFit: true,
            data: Vdata,
            angleField: "value",
            colorField: "type",
            color: this.chartData.datasets[0].backgroundColor,
            label: {
                visible: true,
                type: "inner"
            },
            legend: {
                position: "bottom-center"
            }
        });
        this.antv.render();
    },
    destroyed() {
        this.antv.destroy();
    },
    computed: {}
};
</script>
<style lang="scss" scoped>
.title {
    font-size: 20px;
}
</style>
