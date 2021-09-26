// Search Filter
$(function () {
	var categoryContent = [		
		{ category: 'Electronics', title: 'Cameras' },
		{ category: 'Electronics', title: 'Computers' },
		{ category: 'Electronics', title: 'Headphones' },		
		{ category: 'Electronics', title: 'Home Theater' },
		{ category: 'Electronics', title: 'Mobiles' },		
		{ category: 'Electronics', title: 'Accessories' },
		{ category: 'Personal Care', title: 'Beauty Tools' },		
		{ category: 'Personal Care', title: 'Diet Nutrition' },
		{ category: 'Personal Care', title: 'Hair Care' },
		{ category: 'Personal Care', title: 'Personal Care' },
		{ category: 'Personal Care', title: 'Skin Care' },
		{ category: 'Fashion', title: 'Bags Backpacks' },
		{ category: 'Fashion', title: 'Luggage' },
		{ category: 'Fashion', title: 'Wallets' },
		{ category: 'Fashion', title: 'Travel Accessories' },
		{ category: 'Fashion', title: 'Handbags' },
		{ category: 'Grocery', title: 'Cereal Muesli' },
		{ category: 'Grocery', title: 'Coffee' },
		{ category: 'Grocery', title: 'Dried Fruits' },
		{ category: 'Grocery', title: 'Pickles' },
		{ category: 'Grocery', title: 'Snack Foods' },
		{ category: 'Grocery', title: 'Sweets' },
		{ category: 'Grocery', title: 'Chocolates' },
	];

	$('.ui.search')
	.search({
		type: 'category',
		source: categoryContent,
		calssName : "click"
	});
});