VueAdmin.booting((Vue, router, store) => {
    Vue.component('SmallRuralDogLogViewer',require('./components/SmallRuralDogLogViewer.vue').default);
    Vue.component('SmallRuralDogLogViewerShow',require('./components/SmallRuralDogLogViewerShow.vue').default);
});
