<template>
    <div class="m-15">
        <el-card shadow="never" v-loading="loading">
            <el-table :data="grids" v-if="grids">
                <el-table-column
                    :label="item"
                    :prop="key"
                    v-for="(item, key) in headers"
                    :key="key"
                ></el-table-column>
                <el-table-column label="Actions">
                    <template slot-scope="scope">
                        <span class="mr-10"
                            ><el-link
                                type="primary"
                                :underline="false"
                                @click="onView(scope.row.date, 'all')"
                                >查看</el-link
                            ></span
                        >
                        <span class="mr-10"
                            ><el-link
                                :href="
                                    _.replace(
                                        attrs.routers.download,
                                        '##date##',
                                        scope.row.date
                                    )
                                "
                                :underline="false"
                                type="success"
                                target="_black"
                                >下载</el-link
                            ></span
                        >
                        <el-popconfirm
                            :title="
                                'Are you sure you want to delete this log file ' +
                                    scope.row.date +
                                    '?'
                            "
                            @onConfirm="confirmDelete(scope.row.date)"
                        >
                            <el-link
                                slot="reference"
                                type="danger"
                                :underline="false"
                                >删除</el-link
                            >
                        </el-popconfirm>
                    </template>
                </el-table-column>
            </el-table>
            <div class="mt-15 flex justify-between">
                <el-button
                    @click="getListLogs(prev)"
                    :disabled="prev == null"
                    type="primary"
                >
                    Prev
                </el-button>
                <el-button
                    @click="getListLogs(next)"
                    :disabled="next == null"
                    type="primary"
                >
                    Next
                </el-button>
            </div>
        </el-card>
    </div>
</template>
<script>
export default {
    props: {
        attrs: Object
    },
    data() {
        return {
            headers: null,
            rows: null,
            logs: null,
            deleteModalOpen: false,
            deleting: null,
            next: null,
            prev: null,
            loading: false
        };
    },
    mounted() {
        this.getListLogs();
    },
    methods: {
        getListLogs(url) {
            url = url || this.attrs.routers.get_list_logs;
            this.loading = true;
            this.$http
                .get(url)
                .then(data => {
                    this.headers = data.headers;
                    this.rows = data.rows;
                    this.logs = data.rows.data;

                    this.prev = data.rows.prev_page_url;
                    this.next = data.rows.next_page_url;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        confirmDelete(date) {
            let url = this.attrs.routers.delete + "?date=" + date;

            this.$http.delete(url).then(res => {
                this.$message.success("删除成功");
                this.$delete(this.logs, date);
                this.$emit("onDelete");
            });
        },
        onView(date, level) {
            this.$router.push(this.$route.path + "/" + date);
        }
    },
    computed: {
        grids() {
            return this.logs ? this._.map(this.logs, item => item) : [];
        }
    }
};
</script>
<style lang="scss" scoped>
.el-link {
    font-size: 12px;
}
</style>
