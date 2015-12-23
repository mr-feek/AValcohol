/**
 * This class is the parent view class. It will keep track of all orders and update
 * the list of orders accordingly
 */
var SearchView = React.createClass({
	render: function() {
		return (
			<div>
				<h3>Search</h3>
				This does a search locally for any orders PENDING or OUT FOR DELIVERY. Does not search
				for any other orders. Will scroll to the order for you.
				<div className="row">
					<div className="small-12 large-6 columns">
						<label>Order ID
							<input type="text" className="by-id" />
						</label>
						<a onClick={this.findById} className="button">go</a>
					</div>
					<div className="small-12 large-6 columns">
						<label>First + Last Name
							<input type="text" className="by-name"/>
						</label>
						<a onClick={this.findByName} className="button">go</a>
					</div>
				</div>
			</div>
		);
	},

	findById: function() {
		var val = $('.by-id').val();
		var searchVal = "Order ID: " + val;
		this.search(searchVal);
	},

	findByName: function() {
		var val = $('.by-name').val();
		var searchVal = "Name: " + val;
		this.search(searchVal)
	},

	search: function(value) {
		/*
		for remote...
		$.post('/api/order/search',
			{
				delimeter: delimeter,
				value: value
			},
			function(result) {

			}
		).fail(function(result) {

		});
		*/

		var div = $('.order:contains(' + value + ')');
		div.get(0).scrollIntoView(true);
	}

});