import React from 'react';
import {RouterContext, hashHistory, Link, Redirect} from 'react-router';
import AppBar from 'material-ui/AppBar';

import Drawer from 'material-ui/Drawer';
import Divider from 'material-ui/Divider';
import MenuItem from 'material-ui/MenuItem';
import RaisedButton from 'material-ui/RaisedButton';
import Subheader from 'material-ui/Subheader';

class Home extends React.Component {

    constructor(props, context) {
        super(props, context);
        this.state = { open: false };
    }

    componentDidMount() {
        $.get('/status', (res) => {
            if (!res.body.user) {
                this.context.router.push('/sign-in');
            } else {
                this.context.onMessage(`hello, ${res.body.user.username}`);
                this.setState({ user: res.body.user });
            }
        })
    }

    getChildContext() {
        return {
            user: this.state.user
        }
    }

    handleToggle = () => this.setState({ open: !this.state.open })

    handleSystem = () => {
        this.context.router.push('/home/system');
        this.handleToggle();
    }
    handleBooking = () => {
        this.context.router.push('/home/booking');
        this.handleToggle();
    }
    handleUser = () => {
        this.context.router.push('/home/user');
        this.handleToggle();
    }

    handleArbitration = () => {
        this.context.router.push('/home/arbitration');
        this.handleToggle();
    }

    handleSignout = () => {
        $.get('/signout', (data) => {
            if (data.code == 0) {
                this.context.router.push('/sign-in');
            }
        })
        this.handleToggle();
    }

    render() {
        return (
            <div>
                <AppBar
                    title={this.props.location.pathname.split('/').pop().toUpperCase() }
                    onLeftIconButtonTouchTap={this.handleToggle}
                    />

                <Drawer
                    open={this.state.open}
                    docked={false}
                    onRequestChange={this.handleToggle}
                    style={{
                        top: '64px'
                    }}
                    >
                    <Subheader>Administration</Subheader>
                    <MenuItem onTouchTap={this.handleSystem}>System Administrator</MenuItem>
                    <MenuItem onTouchTap={this.handleBooking}>Booking Administrator</MenuItem>
                    <MenuItem onTouchTap={this.handleUser}>Users</MenuItem>
                    <Divider></Divider>
                    <Subheader>Transition</Subheader>
                    <MenuItem onTouchTap={this.handleArbitration}>Arbitration</MenuItem>
                    <Divider></Divider>
                    <Subheader>Others</Subheader>
                    <MenuItem onTouchTap={this.handleSignout}>Sign Out</MenuItem>

                </Drawer>

                {this.props.children}
            </div>
        )
    }
}

Home.childContextTypes = {
    user: Object
};

Home.contextTypes = {
    onMessage: React.PropTypes.func,
    router: Object
}

export default Home;