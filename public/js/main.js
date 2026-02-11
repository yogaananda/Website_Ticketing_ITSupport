document.addEventListener("DOMContentLoaded", function () {
    
    /**
     * ==========================================
     * 1. GRAFIK MINGGUAN (GROUPED BAR CHART)
     * ==========================================
     */
    const weeklyChartElement = document.getElementById("column-chart");
    
    if (weeklyChartElement) {
        const wLabels = window.chartData ? window.chartData.labels : [];
        const wDataSelesai = window.chartData ? window.chartData.completed : [];
        const wDataPending = window.chartData ? window.chartData.pending : [];

        const weeklyOptions = {
            colors: ["#8DA2FB", "#CDDBFE"], 
            series: [
                { name: "Selesai", data: wDataSelesai },
                { name: "Belum Selesai", data: wDataPending }
            ],
            chart: {
                type: "bar",
                height: "320px",
                fontFamily: "Inter, sans-serif",
                toolbar: { show: false },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "60%",
                    borderRadius: 4,
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"],
            },
            xaxis: {
                categories: wLabels,
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            // PERBAIKAN: Memaksa angka bulat di sumbu Y
            yaxis: { 
                show: false,
                labels: {
                    formatter: function (val) {
                        return val.toFixed(0);
                    }
                }
            },
            grid: { 
                borderColor: '#F3F4F6', 
                strokeDashArray: 4,
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                markers: { radius: 12 },
                itemMargin: { horizontal: 10, vertical: 0 }
            },
            tooltip: {
                shared: true,
                intersect: false,
                // PERBAIKAN: Memaksa angka bulat di Tooltip
                y: {
                    formatter: function (val) {
                        return val.toFixed(0);
                    }
                }
            }
        };

        const weeklyChart = new ApexCharts(weeklyChartElement, weeklyOptions);
        weeklyChart.render();
    }


    /**
     * ==========================================
     * 2. GRAFIK TAHUNAN (AREA CHART)
     * ==========================================
     */
    const yearlyChartElement = document.getElementById("yearlyChart");

    if (yearlyChartElement) {
        const yLabels = window.chartData ? window.chartData.yearlyLabels : [];
        const yDataSelesai = window.chartData ? window.chartData.yearlyCompleted : [];
        const yDataPending = window.chartData ? window.chartData.yearlyPending : [];

        const yearlyOptions = {
            colors: ["#8DA2FB", "#CDDBFE"], 
            series: [
                { name: 'Selesai', data: yDataSelesai }, 
                { name: 'Pending', data: yDataPending }
            ],
            chart: {
                height: 320,
                type: 'area',
                fontFamily: "Inter, sans-serif",
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: yLabels,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            // PERBAIKAN: Memaksa angka bulat di sumbu Y
            yaxis: { 
                show: false,
                labels: {
                    formatter: function (val) {
                        return val.toFixed(0);
                    }
                }
            },
            grid: {
                borderColor: '#F3F4F6',
                strokeDashArray: 4,
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                markers: { radius: 12 }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                // PERBAIKAN: Memaksa angka bulat di Tooltip
                y: {
                    formatter: function (val) {
                        return val.toFixed(0);
                    }
                }
            }
        };

        const yearlyChart = new ApexCharts(yearlyChartElement, yearlyOptions);
        yearlyChart.render();
    }
    
});

