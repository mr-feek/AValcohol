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
		this.pusher = new Pusher('a09bccbd7b4fc81960d2', {
			encrypted: true
		});

		this.channel = this.pusher.subscribe('dev.orders'); // CHANGE THIS FOR PRODUCTION
	},

	componentDidMount: function() {
		// subscribe to new orders being sent via pusher
		this.channel.bind('App\\Events\\OrderWasSubmitted', function(object) {
			alert('new order!');
			this.setState({	orders: this.state.orders.concat(object.order)	});
		}, this);

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
			<OrderList orders={this.state.orders} updateStatus={this.updateOrderStatus} />
		);
	},

	updateOrderStatus: function(order, status, evt) {
		evt.preventDefault();

		if (status === 'delivered') {
			var message = 'PLEASE DOUBLE CHECK THIS, THERE IS NO GOING BACK! | ID: ' + order.id + ' | Product: ' + order.product.name + ' | Name: ' + order.user.last_name + ' | Address: ' + order.address.street;
			result = confirm(message);

			if (!result) {
				return; // gtfo
			}
		}

		$.post(
			'/api/order/status',
			{
				order_id: order.id,
				status: status
			},
			function(result) {
				// just an extra precaution...
				if (status != result.status) {
					alert('something went horribly wrong with updating that status...');
					return;
				}

				// update the order status locally
				order.status = status;

				var orders = this.state.orders;

				// if delivered, remove it from the list of orders
				if (order.status === 'delivered') {
					for (var i = 0; i < orders.length; i++) {
						if(orders[i].id === order.id) {
							orders.splice(i, 1); // removes 1 element from position i
							break;
						}
					}

					this.setState({
						orders: orders
					});

					return;
				}

				this.setState({
					orders: orders.concat(order)
				});
			}.bind(this)
		).fail(function(result) {
			console.log(result);
		});
	}
});