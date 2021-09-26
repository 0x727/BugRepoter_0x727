var cleave = new Cleave('#phone-format-us', {
	phone: true,
	phoneRegionCode: 'US'
});

var cleave = new Cleave('#phone-format-in', {
	phone: true,
	phoneRegionCode: 'IN'
});

var cleave = new Cleave('#phone-format-br', {
	phone: true,
	phoneRegionCode: 'BR'
});


var cleave = new Cleave('#date-formatting', {
	date: true,
	delimiter: '-',
	datePattern: ['Y', 'm', 'd']
});


var cleave = new Cleave('#date-formatting2', {
	date: true,
	datePattern: ['m', 'y']
});



var cleave = new Cleave('#time-formatting', {
	time: true,
	timePattern: ['h', 'm', 's']
});



var cleave = new Cleave('#time-formatting2', {
	time: true,
	timePattern: ['h', 'm']
});



var cleaveG = new Cleave('#input-numeral-thousand', {
	numeral: true,
	numeralThousandsGroupStyle: 'thousand'
});



var cleaveG = new Cleave('#input-numeral-lakh', {
	numeral: true,
	numeralThousandsGroupStyle: 'lakh'
});



var cleave = new Cleave('#input-blocks', {
	blocks: [4, 3, 3, 4],
	uppercase: true
});



var cleave = new Cleave('#input-delimiter', {
	delimiter: '·',
	blocks: [3, 3, 3],
	uppercase: true
});



var cleave = new Cleave('#input-delimiter2', {
	delimiters: ['.', '.', '-'],
	blocks: [3, 3, 3, 2],
	uppercase: true
});



var cleave = new Cleave('#input-prefix', {
	prefix: 'PREFIX',
	delimiter: '-',
	blocks: [6, 4, 4, 4],
	uppercase: true
});



var cleave = new Cleave('#input-credit-card', {
	creditCard: true,
	onCreditCardTypeChanged: function (type) {
		console.log(type)
		var card = $('#creditCardType').find('.'+type);

		if(card.length) {
			card.addClass('highlight');
			card.siblings().removeClass('highlight');
		} else {
			$('#creditCardType .credit-card').removeClass('highlight');
		}
	}
});