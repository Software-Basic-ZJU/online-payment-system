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
import Toggle from 'material-ui/Toggle';

class User extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            users: [],
            loading: true,
        }
    }

    componentDidMount() {
        $.get('/user', (data) => {
            var users = data.body.map((v) => {
                return {
                    "ID": v.user_id,
                    "用户名": v.user_name,
                    "性别": v.gender,
                    "邮箱": v.email,
                    "手机": v.phonenumber,
                    "真实姓名": v.name,
                    "身份证号": v.identity_card,
                    "黑名单": v.is_in_blacklist ? true : false,
                    "实名认证": v.is_name_verified ? true : false,
                }
            });
            if (users.length > 0) {
                users.sort((a, b) => a.user_id - b.user_id);

                this.setState({
                    users: users,
                })
            }
            this.setState({
                loading: false,
            });

        })
    }

    getContent(userIndex, key) {
        switch (key) {
            case '实名认证':
                return <Toggle
                    key={userIndex}
                    toggled={this.state.users[userIndex][key] ? true : false}
                    onToggle={this.handleNameVerified(userIndex) }
                    />;
            case '黑名单':
                return <Toggle
                    key={userIndex}
                    toggled={this.state.users[userIndex][key] ? true : false}
                    onToggle={this.handleBlacklist(userIndex) }
                    />;
            default:
                return this.state.users[userIndex][key];
        }
    }

    handleNameVerified(userIndex) {
        return (event, toggled) => {
            var user = this.state.users[userIndex];
            user["实名认证"] = toggled;
            $.ajax(`/user/${user.ID}/realname`, {
                method: 'put',
                data: {
                    flag: toggled
                }
            }, (data) => {
                if (data.code !== 0) {
                    user["实名认证"] = !toggled;
                }
                this.setState({
                    users: this.state.users
                });
            });
            this.setState({
                users: this.state.users
            });
        }
    }

    handleBlacklist(userIndex) {
        return (event, toggled) => {
            var user = this.state.users[userIndex];
            user["黑名单"] = toggled;
            $.ajax(`/user/${user.ID}/black-list`, {
                method: 'put',
                data: {
                    flag: toggled
                }
            }, (data) => {
                if (data.code !== 0) {
                    user["黑名单"] = !toggled;
                }
                this.setState({
                    users: this.state.users
                });
            });
            this.setState({
                users: this.state.users
            });
        }
    }

    render() {
        return (
            <div>
                {
                    this.state.loading ?
                        <CircularProgress
                            size={1.5}
                            style={{
                                position: 'fixed',
                                top: window.innerHeight / 2 - 52,
                                left: window.innerWidth / 2 - 52
                            }}
                            /> : null
                }
                <Table
                    fixedHeader={true}
                    selectable={false}

                    >
                    <TableHeader
                        displaySelectAll={false}
                        adjustForCheckbox={false}
                        >
                        <TableRow>
                            {
                                this.state.users.length > 0 ?
                                    Object.keys(this.state.users[0]).map((v, i) => <TableHeaderColumn key={i}>{v}</TableHeaderColumn>) :
                                    null
                            }
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        displayRowCheckbox={false}
                        >
                        {
                            this.state.users.map((v, i) => (
                                <TableRow key={i}>
                                    {
                                        Object.keys(v).map((key, index) => <TableRowColumn key={index}>{this.getContent(i, key) }</TableRowColumn>)
                                    }
                                </TableRow>
                            ))
                        }
                    </TableBody>
                </Table>
            </div>
        );
    }
}
User.contextTypes = {
    router: Object,
    onMessage: React.PropTypes.func
}

export default User;