jQuery(function () {

    var Chart = function (el, options) {

        this.options = options || {};
        this.$el = jQuery(el);

        this.data = JSON.parse(this.$el.attr(this.options.dataAttr) || '[]');
        this.type = this.$el.attr(this.options.typeAttr) || 'LineChart';

        this.instance = new google.visualization[this.type](this.$el.get(0));
        this.instance.draw(
            google.visualization.arrayToDataTable(this.data),
            options.chartOptions[this.type]
        );
    };

    var renderCharts = function () {
        jQuery('[data-tp-chart-canvas]').each(function () {
            jQuery(this).data('chart_instance', new Chart(this, googleChartOptions));
        });
    };

    jQuery(document).on('totalpoll.after.ajax', renderCharts);
    jQuery(window).on('resize', renderCharts);

    if (window['google'] !== undefined) {
        google.setOnLoadCallback(renderCharts);
    }

});