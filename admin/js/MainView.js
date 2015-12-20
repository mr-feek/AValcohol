/**
 * This class is the parent view class. It will keep track of all orders and update
 * the list of orders accordingly
 */
var MainView = React.createClass({
	getInitialState: function() {
		return {
			orders: []
		};
	},

	componentWillMount: function() {
		/**
		this.pusher = new Pusher('c644fb6bfa1ca7b73b06', {
			encrypted: true
		});

		this.channel = this.pusher.subscribe('orders');
		 */
	},

	componentDidMount: function() {
		// subscribe to new orders being sent via pusher
		/*
		this.channel.bind('new_order', function(order) {
			this.setState({	orders: this.state.orders.concat(order)	});
		}, this);
		*/

		// fetch all pending orders and orders out for delivery from the server
		$.get('/api/order/pending-and-out-for-delivery', function(result) {
			if (this.isMounted()) {
				var orders = result.orders;
				this.setState({
					orders: this.state.orders.concat(orders)
				});
			}
		}.bind(this));
	},

	render: function() {
		return (
			<OrderList orders={this.state.orders} />
		);
	}
});