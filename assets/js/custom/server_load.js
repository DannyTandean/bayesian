  'use strict';
$(document).ready(function() {
    dashboardEcharts();
});

$(window).on('resize',function() {
    dashboardEcharts();
});

function dashboardEcharts() {

    /*server-load*/
    var myChartGauge = echarts.init(document.getElementById('server-load')); 
            
    var optionGauge = {
                    
                        tooltip : {
                            formatter: "{b} : {c}%"
                        },
                        toolbox: {
                            show : false,
                            feature : {
                                mark : {show: false},
                                restore : {show: false},
                                saveAsImage : {show: true}
                            }
                        },
                        series : [
                                    {
                                        name:'Server Load',
                                        type:'gauge',
                                        center: ['50%', '50%'],
                                        radius: ['0%', '100%'],
                                        axisLine: {
                                            show: true,
                                            lineStyle: {
                                                color: [
                                                    [0.2, '#1ABC9C'],
                                                    [0.8, '#64DDBB'],
                                                    [1, '#1ABC9C']
                                                ],
                                                width: 10
                                            }
                                        }  ,
                                        title: {
                                            show : false,
                                            offsetCenter: [0, '120%'],
                                            textStyle: {
                                                color: '#1ABC9C',
                                                fontSize : 15
                                            }
                                        }  ,
                                        detail: {
                                            show : true,
                                            backgroundColor: 'rgba(0,0,0,0)',
                                            borderWidth: 0,
                                            borderColor: '#ccc',
                                            width: 100,
                                            height: 40,
                                            offsetCenter: [0, '40%'],
                                            formatter:'{value}%',
                                            textStyle: {
                                                color: 'auto',
                                                fontSize : 20
                                            }
                                        },
                                       
                                        data:[{value: 50, name: 'Server Load (MB)'}]
                                    }
                                ]
                    };

    gauge_load_chart(optionGauge);
    // var timeTicket = setInterval(function (){
    //
    //   gauge_load_chart(optionGauge);
    // },2000);

    function gauge_load_chart(optionGauge){
        optionGauge.series[0].data[0].value = (Math.random()*100).toFixed(2) - 0;
        myChartGauge.setOption(optionGauge,true);
    }
};