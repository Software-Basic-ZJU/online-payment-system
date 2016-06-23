import React from 'react';
import ReactDOM from 'react-dom';
import { Router, RouterContext, Route, Link, IndexRoute, IndexRedirect, hashHistory } from 'react-router';
import Root from './components/root';
import Home from './components/home';
import SignIn from './components/sign-in';
import System from './components/system';
import Booking from './components/booking';
import User from './components/user';
import Welcome from './components/welcome';
import Arbitration from './components/arbitration';

const App = () => (
  <Router history={hashHistory}>
    <Route path="/" component={Root}>
      <IndexRedirect to="home"/>
      <Route path="home" component={Home}>
        <IndexRedirect to="welcome"/>
        <Route path="welcome" component={Welcome}></Route>
        <Route path="system" component={System}></Route>
        <Route path="booking" component={Booking}></Route>
        <Route path="user" component={User}></Route>
        <Route path="arbitration" component={Arbitration}></Route>
      </Route>
      <Route path="sign-in" component={SignIn}></Route>
    </Route>
  </Router>
);

let app = document.createElement('div');
ReactDOM.render(<App />, app);
document.body.appendChild(app);