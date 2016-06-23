import React from 'react';
import {Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn} from 'material-ui/Table';
import TextField from 'material-ui/TextField';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import {Card, CardActions, CardHeader, CardText} from 'material-ui/Card';
import FloatingActionButton from 'material-ui/FloatingActionButton';
import ContentAdd from 'material-ui/svg-icons/content/add';
import AppBar from 'material-ui/AppBar';
import Dialog from 'material-ui/Dialog';
import CircularProgress from 'material-ui/CircularProgress';


class System extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            admins: [],
            nextAdminID: 10000,
            addAdminOpen: false,
            loading: true,
        }

        this.handleAddAdminOpen = () => { this.setState({ addAdminOpen: true }) }

        this.handleAddAdmin = () => {
            console.log(this.refs);
            var nextUsername = this.refs.nextUsername.input.value;
            var nextPassword = this.refs.nextPassword.input.value;
            if (!nextUsername || !nextPassword) {
                console.log('empty');
            } else if (this.state.admins.some(v => v.username == nextUsername)) {
                console.log('exist');
            } else {
                // push before post
                this.state.admins.push({
                    admin_id: this.state.nextAdminID,
                    username: nextUsername
                })
                this.setState({ nextAdminID: this.state.nextAdminID + 1 });
                $.post('/system', {
                    username: nextUsername,
                    password: nextPassword
                }, (data) => {
                    if (data.code == 0) {
                        console.log('suc');
                    } else {
                        // roll back
                        this.state.admins.pop();
                        this.setState({ nextAdminID: this.state.nextAdminID - 1 });
                    }
                });
            }
        }
        this.handleClose = () => {
            this.setState({ addAdminOpen: false });
            this.refs.nextUsername.input.value = '';
            this.refs.nextPassword.input.value = '';
        }

        this.handleDelete = () => {
            this.context.onMessage('This feature is unavailable now');
        }
    }

    componentDidMount() {
        $.get('/system', (data) => {
            if (data.code == 0) {
                if (data.body.length > 0) {
                    data.body.sort((a, b) => a.admin_id - b.admin_id);
                    this.setState({
                        admins: data.body,
                        nextAdminID: data.body.slice(-1)[0].admin_id + 1,
                        loading: false
                    })
                } else {
                    this.setState({
                        loading: false,
                    });
                }
            }
        })
    }


    render() {
        return (
            <div>
                {this.state.loading ?
                    <CircularProgress
                        size={1.5}
                        style={{
                            position: 'fixed',
                            top: window.innerHeight / 2 - 52,
                            left: window.innerWidth / 2 - 52
                        }}
                        /> : null}
                <FloatingActionButton
                    onTouchTap={this.handleAddAdminOpen}
                    style={{
                        position: 'fixed',
                        bottom: '30px',
                        right: '30px'
                    }}
                    >
                    <ContentAdd></ContentAdd>
                </FloatingActionButton>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHeaderColumn>ID</TableHeaderColumn>
                            <TableHeaderColumn>Username</TableHeaderColumn>
                            <TableHeaderColumn>Operation</TableHeaderColumn>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {
                            this.state.admins.map((v, i) => (
                                <TableRow key={i}>
                                    <TableRowColumn>{v.admin_id}</TableRowColumn>
                                    <TableRowColumn>{v.username}</TableRowColumn>
                                    <TableRowColumn>
                                        <FlatButton
                                            label="Delete"
                                            onTouchTap={this.handleDelete}
                                            />
                                    </TableRowColumn>
                                </TableRow>
                            ))
                        }
                    </TableBody>
                </Table>
                <Dialog
                    title="Add new system administrator"
                    open={this.state.addAdminOpen}
                    actions={[
                        <FlatButton label="add" onTouchTap={this.handleAddAdmin}/>,
                        <FlatButton label="close" onTouchTap={this.handleClose}/>
                    ]}
                    >
                    <TextField
                        type="text"
                        floatingLabelText="ID"
                        disabled={true}
                        value={this.state.nextAdminID}
                        />
                    <br/>
                    <TextField
                        type="text"
                        floatingLabelText="Username"
                        ref="nextUsername"
                        />
                    <br/>
                    <TextField
                        type="password"
                        floatingLabelText="Password"
                        ref="nextPassword"
                        />
                </Dialog>
            </div>
        );
    }
}
System.contextTypes = {
    router: Object,
    onMessage: React.PropTypes.func
}

export default System;