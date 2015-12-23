var OrderList = React.createClass({
	render: function() {
		var list = this.props.orders.map(function(order){
			return  [
				<div className="order panel">
					<li>Order ID: {order.id}</li>
					<ul>
						Product Details
						<ul>
							<li>Product Name: {order.product.name}</li>
							<li>Contains: {order.product.contains} {order.product.container}(s) {order.product.ounces}oz</li>
						</ul>

						Order Details
						<ul>
							<li>Order Status: {order.status}</li>
							<li>Time Placed: {order.created_at}</li>
							<li>Amount Paid: {order.amount}</li>
						</ul>

						User Details
						<ul>
							<li>Name: {order.user.first_name} {order.user.last_name}</li>
							<li>Phone Number: {order.user.phone_number}</li>
							<li>Email: {order.user.email}</li>
						</ul>

						Delivery Address
						<ul>
							<li>{order.address.street}, {order.address.city}, {order.address.state} ({order.address.zipcode})</li>
						</ul>

						Actions
						<ul>
							<li><a onClick={this.props.updateStatus.bind(null, order, 'out-for-delivery')} className="button" href="#">Mark as out for delivery</a></li>
							<li><a onClick={this.props.updateStatus.bind(null, order, 'delivered')} className="button" href="#">Mark as delivered</a></li>
						</ul>
					</ul>
				</div>
			]
		}, this);

		return (
			<ul>
				{list}
			</ul>
		);
	}
});