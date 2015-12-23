var Page = React.createClass({
	getInitialState: function() {
		return {
			environment: null
		};
	},

	componentDidMount: function() {
		$.get('/api/environment', function(env) {
			if (this.isMounted()) {
				this.setState({
					environment: env
				});
			}
		}.bind(this));
	},

	/**
	 * blocks until the environment is found... not ideal but whatever im tired and it works
	 * @returns {XML}
	 */
	render: function() {
		if (! this.state.environment) { return <p>waiting for environment...</p>;	}

		return (
			<div>
				<SearchView />
				<MainView environment={this.state.environment} />
			</div>
		);
	}
});