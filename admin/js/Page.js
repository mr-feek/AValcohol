var Page = React.createClass({
	getInitialState: function() {
		return {
			username: "default-admin"
		};
	},

	render: function() {
		return (
			<div>
				<SearchView />
				<MainView />
			</div>
		);
	}
});