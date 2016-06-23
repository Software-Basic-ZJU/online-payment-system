import React from 'react';
import {Link} from 'react-router';
import AppBar from 'material-ui/AppBar';
import Dialog from 'material-ui/Dialog';
import TextField from 'material-ui/TextField';
import RaisedButton from 'material-ui/RaisedButton';

class SignIn extends React.Component {
    constructor(props, context) {
        super(props, context);
        console.log(props, context);
        this.state = {
            errUsername: '',
            errPassword: ''
        }

        this.handleSubmit = () => {
            var username = this.refs.username.input.value;
            var password = this.refs.password.input.value;
            this.setState({
                errUsername: username ? '' : 'miss username',
                errPassword: password ? '' : 'miss password'
            });

            if (!username || !password) return;
            $.post('/signin', {
                username: this.refs.username.input.value,
                password: this.refs.password.input.value
            }, res => {
                if (res.code == 0) {
                    context.router.push('/home');
                } else if (res.code == 1) {
                    context.onMessage('wrong username or password');
                } else if (res.code == 404) {
                    context.onMessage('no such administrator');
                } else if (res.code < 0) {
                    context.onMessage('BUG occured');
                }
            })
        }
        this.submitWhenEnter = (a) => {
            if (a.key == 'Enter')
                this.handleSubmit();
        }
    }
    componentDidMount() {
        $.get('/status', data => {
            if (data.body.user) this.context.router.push('/home');
            else this.context.onMessage('sign in please');
        })
    }

    render() {

        const actions = [
            <RaisedButton
                label="Sign In"
                primary={true}
                onTouchTap={this.handleSubmit}
                />
        ]

        return (
            <div>
                <Dialog
                    title="Sign in System Administration"
                    modal={false}
                    open={true}
                    actions={actions}
                    >
                    <TextField
                        type="text"
                        ref="username"
                        floatingLabelText="Your username"
                        errorText={this.state.errUsername}
                        onKeyDown={this.submitWhenEnter}
                        />
                    <br/>
                    <TextField
                        type="password"
                        ref="password"
                        floatingLabelText="Your password"
                        errorText={this.state.errPassword}
                        onKeyDown={this.submitWhenEnter}
                        />
                </Dialog>
            </div>
        )
    }
}

SignIn.contextTypes = {
    router: Object,
    onMessage: React.PropTypes.func,
    socket: Object,
}

export default SignIn;