import React from 'react';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import injectTapEventPlugin from 'react-tap-event-plugin';
import Snackbar from 'material-ui/Snackbar';
injectTapEventPlugin();

const socket = io.connect();
socket.on('message', function(msg){
    console.log(msg);
})

class Root extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            open: false,
            message: '',
            autoHideDuration: 1000,
            messageQueue: []
        }

        this.handleShift = () => {
            this.setState({ open: false, message: this.state.messageQueue.shift() || '' });
            if (this.state.message) this.setState({ open: true });
        }

        this.onMessage = (message) => {
            this.state.messageQueue.push(message);
            if (!this.state.open) this.handleShift();
        }
    }

    getChildContext() {
        return {
            onMessage: this.onMessage,
            socket: socket
        };
    }

    render() {
        return (
            <MuiThemeProvider muiTheme={getMuiTheme() }>
                <div>
                    <Snackbar
                        open={this.state.open}
                        message={this.state.message}
                        action="ok"
                        autoHideDuration={this.state.autoHideDuration}
                        onActionTouchTap={this.handleShift}
                        onRequestClose={this.handleShift}
                        />
                    {this.props.children}
                </div>
            </MuiThemeProvider>
        );
    }
}

Root.childContextTypes = {
    onMessage: React.PropTypes.func,
    socket: Object
};

export default Root;