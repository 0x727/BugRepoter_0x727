var options1 = {
  series: [{
	  data: [5, 10, 20, 15, 25, 35, 25]
	}],
	  chart: {
	  type: 'bar',
	  width: 60,
	  height: 60,
	  sparkline: {
	    enabled: true
	  }
	},
	plotOptions: {
	  bar: {
	    columnWidth: '75%'
	  }
	},
	xaxis: {
	  crosshairs: {
	    width: 1
	  },
	},
	tooltip: {
	  fixed: {
	    enabled: false
	  },
	  x: {
	    show: false
	  },
	  marker: {
	    show: false
	  }
	},
	colors: ['#1273eb'],
	xaxis: {
    categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return val + ' Reviews'
      }
    }
  },
};

var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
chart1.render();
















var options2 = {
  series: [{
	  data: [5, 15, 10, 15, 30, 20, 15]
	}],
	  chart: {
	  type: 'bar',
	  width: 60,
	  height: 60,
	  sparkline: {
	    enabled: true
	  }
	},
	plotOptions: {
	  bar: {
	    columnWidth: '75%'
	  }
	},
	labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
	xaxis: {
	  crosshairs: {
	    width: 1
	  },
	},
	tooltip: {
	  fixed: {
	    enabled: false
	  },
	  x: {
	    show: false
	  },
	  marker: {
	    show: false
	  }
	},
	colors: ['#1273eb'],
	xaxis: {
    categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return val + ' Ratings'
      }
    }
  },
};

var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
chart2.render();
















var options3 = {
  series: [{
	  data: [10, 15, 10, 20, 15, 20, 25]
	}],
	  chart: {
	  type: 'bar',
	  width: 60,
	  height: 60,
	  sparkline: {
	    enabled: true
	  }
	},
	plotOptions: {
	  bar: {
	    columnWidth: '75%'
	  }
	},
	labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
	xaxis: {
	  crosshairs: {
	    width: 1
	  },
	},
	tooltip: {
	  fixed: {
	    enabled: false
	  },
	  x: {
	    show: false
	  },
	  marker: {
	    show: false
	  }
	},
	colors: ['#5dab18'],
	xaxis: {
    categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return val + ' Signups'
      }
    }
  },
};

var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
chart3.render();