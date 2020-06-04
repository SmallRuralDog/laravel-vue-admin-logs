<template>
    <div class="m-15">
        <el-page-header @back="goBack" :content="attrs.date"> </el-page-header>
        <el-row class="mt-20" gutter="20">
            <el-col :span="3">
                <div shadow="never" title="Level">
                    <el-menu :default-active="defaultActive" @select="onselect">
                        <el-menu-item
                            :index="key"
                            v-for="(menu, key) in menus"
                            :key="key"
                        >
                            <div class="flex justify-between">
                                <div>
                                    <span
                                        class="mr-5"
                                        v-html="menu.icon"
                                    ></span>
                                    <span>{{ key }}</span>
                                </div>
                                <div>
                                    <el-tag
                                        :type="menu.count > 0 ? 'danger' : ''"
                                        >{{ menu.count }}</el-tag
                                    >
                                </div>
                            </div>
                        </el-menu-item>
                    </el-menu>
                </div>
            </el-col>
            <el-col :span="21" v-if="info">
                <el-card shadow="never">
                    <div slot="header">
                        <div>Log Info</div>
                    </div>
                    <div>
                        <span>File Path :</span>
                        <span>{{ info.path }}</span>
                    </div>
                    <el-divider></el-divider>
                    <el-row>
                        <el-col :span="6">
                            <span>Log Entries :</span>
                            <el-tag>{{ info.entries }}</el-tag>
                        </el-col>
                        <el-col :span="6">
                            <span>Size :</span>
                            <el-tag>{{ info.size }}</el-tag>
                        </el-col>
                        <el-col :span="6">
                            <span>Created At :</span>
                            <el-tag>{{ info.created_at }}</el-tag>
                        </el-col>
                        <el-col :span="6">
                            <span>Updated At :</span>
                            <el-tag>{{ info.updated_at }}</el-tag>
                        </el-col>
                    </el-row>
                </el-card>
                <el-card
                    
                    shadow="never"
                    class="mt-20"
                    body-style="padding:0"
                >
                    <div class="flex justify-between " slot="header">
                        <div>List</div>
                        <div v-if="prev != null || next != null">
                            <el-button
                                @click="getLog(prev)"
                                v-if="prev != null"
                                type="primary"
                            >
                                Prev
                            </el-button>
                            <el-button
                                @click="getLog(next)"
                                v-if="next != null"
                                type="primary"
                            >
                                Next
                            </el-button>
                        </div>
                    </div>
                    <div>
                        <el-table :data="entries" v-loading="loading">
                            <el-table-column type="expand">
                                <template slot-scope="props">
                                    <div class="stack-content">
                                        <code> {{ props.row.stack }}</code>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Level"
                                prop="level"
                                width="100"
                            >
                                <template slot-scope="props">
                                    <el-tag
                                        effect="dark"
                                        :type="props.row.level | getType"
                                        >{{ props.row.level }}</el-tag
                                    >
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Time"
                                prop="datetime"
                                width="150"
                            ></el-table-column>
                            <el-table-column
                                label="Header"
                                prop="header"
                            ></el-table-column>
                        </el-table>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
</template>
<script>
export default {
    props: {
        attrs: Object
    },
    data() {
        return {
            defaultActive: "all",
            info: null,
            log: null,
            levels: null,
            level: null,
            entries: null,
            menus: null,
            deleteModalOpen: false,
            deleting: null,
            next: null,
            prev: null,

            stackModalOpen: false,
            stack: null,
            loading: false
        };
    },
    mounted() {
        this.getLog();
    },
    methods: {
        goBack() {
            this.$router.go(-1);
        },
        onselect(level) {
            this.defaultActive = level;
            this.getLog();
        },
        getLog(url) {
            url = url || this.attrs.routers.get;

            url = this._.replace(url, "##level##", this.defaultActive);
            this.loading = true;
            this.$http
                .get(url)
                .then(data => {
                    this.log = data.log;
                    this.levels = data.levels;
                    this.level = data.level;
                    this.entries = this._.map(data.entries.data, item => item);
                    this.menus = data.menus;
                    this.prev = data.entries.prev_page_url;
                    this.next = data.entries.next_page_url;
                    this.info = data.info;
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    },
    filters: {
        getType(type) {
            switch (type) {
                case "error":
                    return "danger";
                    break;
            }
            return "info";
        }
    }
};
</script>
<style lang="scss" scoped>
.mt-20 {
    margin-top: 20px;
}
.stack-content {
    color: #ae0e0e;
    font-family: consolas, Menlo, Courier, monospace;
    font-size: 12px;
    font-weight: 400;
    white-space: pre-line;
    width: 100%;
}
</style>
